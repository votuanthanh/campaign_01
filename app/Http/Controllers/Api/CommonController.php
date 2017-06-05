<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\DB;

class CommonController extends ApiController
{
    public function checkExist(Request $request)
    {
        $data = $request->only('column', 'database', 'value', 'idIgnore');

        return $this->getData(function () use ($data) {
            $query = DB::table($data['database'])->where($data['column'], $data['value']);

            if ($data['idIgnore']) {
                $query->whereNotIn('id', [$data['idIgnore']]);
            }

            $this->compacts['valid'] = $query->exists();
        });
    }
}
