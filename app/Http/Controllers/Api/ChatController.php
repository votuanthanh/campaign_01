<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Repositories\Contracts\UserInterface;
use App\Repositories\Contracts\CampaignInterface;
use App\Repositories\Contracts\RoleInterface;
use LRedis;
use Exception;
use App\Models\User;
use App\Models\Campaign;
use App\Models\Role;

class ChatController extends ApiController
{
    private $redis;
    private $userRepository;
    private $campaignRepository;
    private $roleRepository;

    public function __construct(
        UserInterface $userRepository,
        CampaignInterface $campaignRepository,
        RoleInterface $roleRepository
    ) {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->campaignRepository = $campaignRepository;
        $this->roleRepository = $roleRepository;
        $this->redis = LRedis::connection();
    }

    /**
     * Display a messages of the group chat.
     *
     * @param id of group or id of recevie
     * @return \Illuminate\Http\Response
     */
    public function showMessages(Request $request, $id)
    {
        $groupKey = $this->getNameGroupChat($this->user->id, $id, json_decode($request->type));
        $start = json_decode($request->paginate);
        $stop = $start + config('settings.paginate_default');
        $keyMessages = $this->redis->command('lrange', [
            $groupKey,
            $start,
            $stop,
        ]);

        if (!$keyMessages) {
            return response()->json([
                'status' => NOT_FOUND,
                'continue' => $start === 0,
            ]);
        }

        $keyMessages = is_array($keyMessages) ? $keyMessages : [$keyMessages];
        $messages = [];

        foreach ($keyMessages as $key) {
            $content = json_decode($this->redis->get($key));

            if (!empty($content)) {
                $messages[] = $content;
            }
        }

        $read = !$start ? $this->redis->get('read' . $groupKey) : false;

        if ($read) {
            $read = json_decode($read);
            $time = $read->time;
            $lastMessage = $this->redis->command('lrange', [$groupKey, 0, 0]);
            $read = $read->id == head($lastMessage)
                ? json_decode($this->redis->get($read->id))->userId
                : null;
            $read = $read ? true : false;
        }

        return response()->json([
            'status' => CODE_OK,
            'messages' => $messages,
            'paginate' => ++$stop,
            'continue' => true,
            'read' => $read,
            'time' => ($read) ? $time : null,
        ]);
    }

    /**
     * send message to single chat
     *
     * @return \Illuminate\Http\Response
     */
    public function sendMessage(Request $request)
    {
        if ($request->channel != config('settings.single_chat')) {
            return $this->failAction(config('settings.single_chat'));
        }

        try {
            $keys = $this->setGroupChat($request->receiveUser);

            if ($keys['group'] != $request->groupKey) {
                throw new Exception();
            }

            $data = $this->saveMessage($request->message, $keys, $request->receiveUser);

            return $this->successAction($request->channel, $data);
        } catch (Exception $e) {
            return $this->failAction($request->channel);
        }
    }

    /**
     * send message the group chat.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendMessageToGroup(Request $request)
    {
        if ($request->channel != config('settings.group_chat')) {
            return $this->failAction(config('settings.group_chat'));
        }

        try {
            $campaign = $this->campaignRepository->where('hashtag', $request->receiveUser)->first();

            if ($this->user->cant('joinChat', $campaign)) {
                throw new Exception();
            }

            $keys = $this->setGroupChat($campaign->hashtag, false);

            if (empty($keys['group']) || $keys['group'] != md5($request->groupKey)) {
                throw new Exception();
            }

            $receive = config('settings.head_room_name') . $campaign->hashtag;
            $data = $this->saveMessage($request->message, $keys, $receive);
            $data['groupChat'] = $campaign->hashtag;

            return $this->successAction($request->channel, $data);
        } catch (Exception $e) {
            return $this->failAction($request->channel);
        }
    }

    /**
     * get key if group is one to one or name of group chat
     *
     * @return String name
     */
    private function getNameGroupChat($send, $receive, $status = true)
    {
        if ($status) {
            return ($send > $receive) ? $receive . '-' . $send : $send . '-' . $receive;
        } else {
            $groupName = is_string($send) ? $send : $receive;

            return md5($groupName);
        }
    }

    /**
     * set name or key for group chat and make key for new message
     *
     * @param receive or name of group chat
     * @return array keys
     */
    private function setGroupChat($receive, $status = true)
    {
        $groupKey = $this->getNameGroupChat($this->user->id, $receive, $status);
        $idMessage = $this->user->id . '-' . $receive . '-' . \Carbon\Carbon::now()->format('m/d/Y:H:i:s');

        if (!$groupKey || !$idMessage) {
            throw new Exception();
        }

        $this->redis->lpush($groupKey, $idMessage);

        return [
            'group' => $groupKey,
            'idMessage' => $idMessage,
        ];
    }

    /**
     * save message to redis
     *
     * @param content message, key for message, receive id
     * @return array
     */
    private function saveMessage($content, $keys, $toReceive)
    {
        if (empty($keys['group']) || empty($keys['idMessage']) || trim($content) == null) {
            throw new Exception();
        }

        if (is_numeric($toReceive)) {
            $receive = $this->userRepository->findOrFail($toReceive);
        } else {
            $receive = $this->campaignRepository
                ->where('hashtag', explode(':', $toReceive)[1])
                ->with('media')
                ->first();
        }

        $message = json_encode([
            'groupKey' => $keys['group'],
            'userId' => $this->user->id,
            'avatar' => $this->user->image_thumbnail,
            'name' => $this->user->name,
            'time' => \Carbon\Carbon::now()->format('m-d-Y H:i:s'),
            'message' => $content,
            'receive' => $toReceive,
            'nameReceive' => is_numeric($toReceive) ? $receive->name : $receive->hashtag,
            'avatarReceive' => is_numeric($toReceive)
                ? $receive->image_thumbnail
                : (($receive->media->first()) ? $receive->media->first()->image_thumbnail : null),
        ]);

        $this->redis->set($keys['idMessage'], $message);
        $this->setNotificationWhenSendMessage($keys['group'], $receive);

        $data = [
            'from' => (string)$this->user->id,
            'name' => $this->user->name,
            'to' => $toReceive,
            'groupChat' => $keys['group'],
            'message' => $message,
            'success' => true,
        ];

        return $data;
    }

    private function failAction($channel)
    {
        $this->redis->publish($channel, json_encode([
            'success' => false,
        ]));

        return response()->json([
            'status' => ACCESS_DENIED,
        ]);
    }

    private function successAction($channel, $data)
    {
        $this->redis->publish($channel, json_encode($data));

        return response()->json([
            'status' => CODE_OK,
        ]);
    }

    private function setNotificationWhenSendMessage($groupKey, $receive)
    {
        if ($receive instanceof User) {
            $ids = explode('-', $groupKey);
            $this->storeNotification($groupKey, $ids[0]);
            $this->storeNotification($groupKey, $ids[1]);
        } else {
            $roleIds = $this->roleRepository->whereIn('name', [
                    Role::ROLE_OWNER,
                    Role::ROLE_MODERATOR,
                    Role::ROLE_MEMBER,
                ])
                ->lists('id');
            $ids = $receive->users()
                ->where('users.status', User::ACTIVE)
                ->wherePivotIn('role_id', $roleIds)
                ->wherePivot('status', Campaign::APPROVED)
                ->pluck('users.id');

            foreach ($ids as $id) {
                $this->storeNotification($groupKey, $id);
            }
        }
    }

    private function storeNotification($groupKey, $userId)
    {
        $lastMessage = $this->redis->command('zrange', [config('settings.notifications') . $userId, -1, -1]);
        $score = 0;

        if ($lastMessage) {
            $score = $this->redis->command('zscore', [config('settings.notifications') . $userId, $lastMessage[0]]);
            $score++;
        }

        $this->redis->command('zadd', [config('settings.notifications') . $userId, $score, $groupKey]);
    }

    public function getNotification(Request $request)
    {
        $start = json_decode($request->paginate);
        $stop = $start + config('settings.paginate_notification');
        $keyGroups = $this->redis->command('zrevrange', [
            config('settings.notifications') . $this->user->id,
            $start,
            $stop,
        ]);

        if (!$keyGroups) {
            return response()->json([
                'status' => NOT_FOUND,
                'continue' => $start === 0,
            ]);
        }

        $keyMessage = [];
        $notifications = [];

        foreach ($keyGroups as $key) {
            $idMessage = $this->redis->command('lrange', [$key, 0, 0]);
            $content = json_decode($this->redis->get(head($idMessage)));

            if ($content) {
                $read = null;
                $time = null;

                if (!is_numeric($content->receive)) {
                    $content->groupKey = $content->nameReceive;
                } else {
                    $read = json_decode($this->redis->get('read' . $key));
                    $time = ($read && $read->id === head($idMessage)) ? $read->time : null;
                    $read = ($read && $read->id === head($idMessage));
                }

                $notifications[] = [
                    'content' => $content,
                    'isRead' => $read,
                    'time' => $time,
                ];
            }
        }

        return response()->json([
            'status' => CODE_OK,
            'notifications' => $notifications,
            'paginate' => ++$stop,
            'continue' => true,
        ]);
    }
}
