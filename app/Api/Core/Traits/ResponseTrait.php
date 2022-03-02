<?php

namespace App\Api\Core\Traits;

use App\Api\Core\Helpers\StatusCodeHelper;

trait ResponseTrait
{
    public function jsonResponse($data = null, string $messages = null, int $statusCode, $links = null, $meta = null)
    {
        $array = array(
            'data' => $data,
            'message' => $messages,
            'status' => $statusCode
        );

        if($links) $array['links'] = $links;
        if($meta) $array['meta'] = $meta;

        return response()->json($array, $statusCode);
    }

    public function error(string $errorMessage, int $statusCode = null)
    {
        if(!$statusCode) $statusCode = StatusCodeHelper::STATUS_NOT_FOUND;
        return $this->jsonResponse(null, $errorMessage, $statusCode);
    }

    public function success(string $message = null, object $data = null, int $statusCode = null)
    {
        if(!$statusCode) $statusCode = StatusCodeHelper::STATUS_OK;
        return $this->jsonResponse($data, $message, $statusCode);
    }

    public function successPaginated(string $message = null, array $data = null, int $statusCode = null)
    {
        if(!$statusCode) $statusCode = StatusCodeHelper::STATUS_OK;
        return $this->jsonResponse($data['data'], $message, $statusCode, $data['links'], $data['meta']);
    }

    public function accessToken(string $accessToken)
    {
        return response()->json([
            'accessToken' => $accessToken,
            'tokenType'    => 'Bearer',
            'status' => StatusCodeHelper::STATUS_OK
        ], StatusCodeHelper::STATUS_OK);
    }
}
