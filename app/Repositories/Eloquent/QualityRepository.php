<?php

namespace App\Repositories\Eloquent;

use App\Models\Quality;
use App\Repositories\Contracts\QualityInterface;

class QualityRepository extends BaseRepository implements QualityInterface
{
    public function model()
    {
        return Quality::class;
    }

    public function getOrCreate($data)
    {
        if (!$data) {
            return null;
        }

        foreach ($data as $key => $value) {
            $nameQuality = $this->where('name', $value['quality'])->first();

            if (!$nameQuality) {
                //field name of quality don't exitst then create new quality and donationType
                $quality = $this->create(['name' => $value['quality']]);
                $donationType = $quality->donationTypes()->create(['name' => $value['type']]);
                $result[$key]['donation_type_id'] = $donationType->id;
            } else {
                $donationType = $nameQuality->donationTypes()->pluck('name', 'id')->toArray();
                $id = array_search($value['type'], $donationType);

                if ($id) {
                    //field name of quality is exitst then get id of record in donationType.
                    $result[$key]['donation_type_id'] = $id;
                } else {
                    //quality is exitst va donation_type don't exitst then create create new in donationType
                    $newId = $nameQuality->donationTypes()->create(['name' => $value['type']])->id;
                    $result[$key]['donation_type_id'] = $newId;
                }
            }

            $result[$key]['goal'] = $value['goal'];
        }

        return $result;
    }
}
