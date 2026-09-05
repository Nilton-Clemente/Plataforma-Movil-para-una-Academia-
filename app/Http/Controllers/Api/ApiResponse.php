<?php

namespace App\Http\Controllers\Api;

trait ApiResponse
{
    protected function success(mixed $data = null, string $message = 'OK', int $status = 200)
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'errors' => null,
        ], $status);
    }

    protected function error(string $message, int $status = 400, mixed $errors = null)
    {
        return response()->json([
            'data' => null,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }
}
