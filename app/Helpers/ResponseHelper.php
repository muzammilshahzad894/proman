<?php

namespace App\Helpers;

class ResponseHelper
{
    public static function jsonResponse($status, $message, $redirect = null, $httpCode = 200)
    {
        $response = [
            'status' => $status,
            'message' => $message,
        ];

        if ($redirect) {
            $response['redirect'] = $redirect;
        }

        return response()->json($response, $httpCode);
    }
}
