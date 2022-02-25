<?php

namespace App\Api\Core\Traits;

trait ResponseTrait
{
    public function jsonResponse($data = null, string $messages = null, int $statusCode) {
        return response()->json([
            'data' => $data,
            'messages'    => $messages,
            'status' => $statusCode
        ], $statusCode);
    }

    public function error(string $errorMessage, int $statusCode = 400, $data = null) {
        return $this->jsonResponse($data, $errorMessage, $statusCode);
    }

    public function success(string $message = null, object $data = null, int $statusCode = 200) {
        return $this->jsonResponse($data, $message, $statusCode);
    }

    public function successBulk(string $message = null, array $data = null, int $statusCode = 200) {
        return $this->jsonResponse($data, $message, $statusCode);
    }

    public function accessToken(string $accessToken){
        return response()->json([
            'access_token' => $accessToken,
            'token_type'    => 'Bearer',
            'status' => 200
        ], 200);
    }
}
