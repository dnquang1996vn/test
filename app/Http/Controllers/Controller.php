<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function responseSuccess($data = null)
    {
        return response([
            'data' => $data
        ]);
    }

    public function responseFail(\Exception $exception)
    {
        return response([
            'message' => 'Something went wrong',
            'errors' => []
        ], $exception->getCode());
    }
}
