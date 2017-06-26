<?php

namespace App\Repositories\Eloquent;

use App\Models\Donation;
use App\Repositories\Contracts\DonationInterface;

class DonationRepository extends BaseRepository implements DonationInterface
{
    public function model()
    {
        return Donation::class;
    }
}
