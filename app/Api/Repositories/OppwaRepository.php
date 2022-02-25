<?php

namespace App\Api\Repositories;

use App\Api\Requests\CreateCheckoutRequest;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

class OppwaRepository
{
    CONST URI_VERSION = '/v1';
    CONST URI_CHECKOUTS = self::URI_VERSION . '/checkouts';
    protected $client;

    function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('OPPWA_API_URL'),
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => 'Bearer ' . env('OPPWA_API_ACCESS_TOKEN')
            ],
        ]);
    }

    public function checkout(CreateCheckoutRequest $data){
        try {
             return json_decode($this->client->request('POST', self::URI_CHECKOUTS, [
                'form_params' => [
                    'entityId' => env('OPPWA_API_ENTITY_ID'),
                    'amount' => $data->amount,
                    'currency' => 'EUR',
                    'paymentType' => 'DB',
                    'merchantTransactionId' => $data->reference
                ]
            ])->getBody()->getContents());
        } catch (\Exception $e) {
            $error =  Str::afterLast($e->getMessage(), 'description":"');
            $error = Str::before($error, '"');
            throw new \Exception($error, $e->getCode());
        }
    }
}
