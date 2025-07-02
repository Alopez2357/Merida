<?php

namespace App\Traits;

trait ApiResponser
{

    protected function successResponse($data, $message = 'Success', $code = 200)
    {
        return response()->json([
            'status' => 'SUCCESS',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function errorResponse($message = null, $code = 404, $errors = null)
    {
        return response()->json([
            'status' => 'FAILED',
            'message' => $message,
            'errors' => $errors
        ], $code);
    }
}
