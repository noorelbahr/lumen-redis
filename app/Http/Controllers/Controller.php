<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * Success response template
     * - - -
     * @param $data
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($data, $code = 200) {
        return response()
            ->json(['data' => $data], $code ? : 200);
    }

    /**
     * Error response template
     * - - -
     * @param $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function error($message, $code = 500) {
        return response()
            ->json(['error_message' => $message], $code ? : 500);
    }
}
