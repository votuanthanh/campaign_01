<?php

namespace App\Events;

class AddActivityHandler
{
    public function handle($data)
    {
        if ($data) {
            $data['model']->activities()->create([
                'user_id' => $data['user_id'],
                'name' => $data['action'],
            ]);
        }
    }
}
