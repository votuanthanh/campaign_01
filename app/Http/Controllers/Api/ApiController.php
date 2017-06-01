<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AbstractController;
use App\Exceptions\Api\NotFoundException;
use App\Exceptions\Api\UnknowException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class ApiController extends AbstractController
{
    protected $guard = 'api';

    protected function trueJson()
    {
        $this->compacts['http_status'] = [
            'code' => CODE_OK,
            'status' => true,
        ];

        return response()->json($this->compacts);
    }

    protected function doAction(callable $callback)
    {
        try {
            if (is_callable($callback)) {
                call_user_func($callback);
            }
        } catch (ModelNotFoundException $e) {
            throw new NotFoundException($e->getMessage(), $e->getCode());
        } catch (NotFoundException $e) {
            throw new NotFoundException($e->getMessage(), $e->getCode());
        } catch (Exception $e) {
            throw new UnknowException($e->getMessage(), $e->getCode());
        }

        return $this->trueJson();
    }
}
