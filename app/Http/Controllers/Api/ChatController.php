<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Repositories\Contracts\UserInterface;
use App\Repositories\Contracts\CampaignInterface;
use LRedis;
use Exception;

class ChatController extends ApiController
{
    private $redis;
    private $userRepository;
    private $campaignRepository;

    public function __construct(UserInterface $userRepository, CampaignInterface $campaignRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->campaignRepository = $campaignRepository;
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

        return response()->json([
            'status' => CODE_OK,
            'messages' => $messages,
            'paginate' => ++$stop,
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

        $message = json_encode([
            'groupKey' => $keys['group'],
            'userId' => $this->user->id,
            'avatar' => $this->user->image_thumbnail,
            'name' => $this->user->name,
            'time' => \Carbon\Carbon::now()->format('m/d/Y H:i:s'),
            'message' => $content,
        ]);

        $this->redis->set($keys['idMessage'], $message);

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
}
