<?php

namespace App\Api\Core\Traits;

use phpDocumentor\Reflection\Types\Object_;
use stdClass;

trait ResponseTrait
{
    public function jsonResponse($data = null, string $messages = null, int $statusCode, $links = null, $meta = null) {
        $array = array(
            'data' => $data,
            'messages' => $messages,
            'status' => $statusCode
        );

        if($links) $array['links'] = $links;
        if($meta) $array['meta'] = $meta;

        return response()->json($array, $statusCode);
    }

    public function error(string $errorMessage, int $statusCode = 400, $data = null) {
        return $this->jsonResponse($data, $errorMessage, $statusCode);
    }

    public function success(string $message = null, object $data = null, int $statusCode = 200) {
        return $this->jsonResponse($data, $message, $statusCode);
    }

    public function successPaginated(string $message = null, array $data = null, int $statusCode = 200) {
        return $this->jsonResponse($data['data'], $message, $statusCode, $data['links'], $data['meta']);
    }

    public function accessToken(string $accessToken){
        return response()->json([
            'access_token' => $accessToken,
            'token_type'    => 'Bearer',
            'status' => 200
        ], 200);
    }
}
