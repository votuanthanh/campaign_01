<?php

namespace App\Http\Controllers\Api;

use App\Models\Donation;
use Illuminate\Http\Request;
use App\Http\Requests\DonationRequest;
use App\Exceptions\Api\UnknowException;
use App\Repositories\Contracts\EventInterface;
use App\Repositories\Contracts\DonationInterface;

class DonationController extends ApiController
{
    protected $eventRepository;
    protected $donationRepository;

    public function __construct(
        DonationInterface $donationRepository,
        EventInterface $eventRepository
    ) {
        parent::__construct();
        $this->donationRepository = $donationRepository;
        $this->eventRepository = $eventRepository;
    }

    public function store(DonationRequest $request)
    {
        $input = $request->only('event_id', 'goal_id', 'value');
        $event = $this->eventRepository->findOrFail($input['event_id']);
        $input['user_id'] = $this->user->id;
        $input['campaign_id'] = $event->campaign_id;

        if ($this->user->cannot('view', $event)) {
            throw new UnknowException('Permission error: User can not donate.');
        }

        $input['status'] = ($this->user->can('manage', $event)) 
            ? Donation::ACCEPT
            : Donation::NOT_ACCEPT;

        return $this->doAction(function () use ($input) {
            $this->compacts['donation'] = $this->donationRepository->create($input);
        });
    }

    public function updateStatus(Request $request, $id)
    {
        $donation = $this->donationRepository->findOrFail($id);

        if (!in_array($request->status, [
            Donation::ACCEPT,
            Donation::NOT_ACCEPT,
        ])) {
            throw new UnknowException('Error: Invalid input.');
        }

        if ($this->user->cannot('manage', $donation)) {
            throw new UnknowException('Permission error: User can not change this donation.');
        }

        return $this->doAction(function () use ($request, $donation) {
            $this->compacts['donation'] = $this->donationRepository->update($donation->id, [
                'status' => $request->status,
            ]);
        });
    }

    public function update(DonationRequest $request, $id)
    {
        $donation = $this->donationRepository->findOrFail($id);
        $data = $request->only('goal_id', 'value');

        if ($this->user->cannot('manage', $donation)) {
            throw new UnknowException('Permission error: User can not change this donation.');
        }

        if ($donation->status && $this->user->id == $donation->user_id) {
            throw new UnknowException('Error: This donation was accepted, you can not edit it.');
        }

        return $this->doAction(function () use ($data, $donation) {
            $this->compacts['donation'] = $this->donationRepository->update($donation->id, $data);
        });
    }

    public function destroy($id)
    {
        $donation = $this->donationRepository->findOrFail($id);

        if ($this->user->cannot('manage', $donation)) {
            throw new UnknowException('Permission error: User can not delete this donation.');
        }

        return $this->doAction(function () use ($donation) {
            $this->compacts['donation'] = $this->donationRepository->delete($donation->id);
        });
    }
}
