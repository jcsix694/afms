<?php

namespace App\Api\Repositories;

use App\Api\Requests\CreateCheckoutRequest;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

class OppwaRepository
{
    CONST URI_VERSION = '/v1';
    CONST URI_CHECKOUTS = '/checkouts';
    CONST URI_PAYMENT = '/payment';
    CONST URI_PAYMENTS = '/payments';
    CONST CODE_TRANSACTION_PENDING='000.200.000';
    CONST CODE_REQUEST_PROCESSED_TEST='000.100.110';
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

    public function createCheckout(CreateCheckoutRequest $data){
        try {
             return json_decode($this->client->request('POST', self::URI_VERSION . self::URI_CHECKOUTS, [
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

    public function getCheckout(string $checkoutId){
        try {
            return json_decode($this->client->request('GET', self::URI_VERSION . self::URI_CHECKOUTS . '/' . $checkoutId . self::URI_PAYMENT,[
                'query' => [
                    'entityId' => env('OPPWA_API_ENTITY_ID'),
                ]
            ])->getBody()->getContents());
        } catch (\Exception $e) {
            $error =  Str::afterLast($e->getMessage(), 'description":"');
            $error = Str::before($error, '"');
            throw new \Exception($error, $e->getCode());
        }
    }

    public function refund($paymentId, $amount){
        try {
            return json_decode($this->client->request('POST', self::URI_VERSION . self::URI_PAYMENTS . '/' . $paymentId, [
                'form_params' => [
                    'entityId' => env('OPPWA_API_ENTITY_ID'),
                    'amount' => $amount,
                    'currency' => 'EUR',
                    'paymentType' => 'DB',
                ]
            ])->getBody()->getContents());
        } catch (\Exception $e) {
            $error =  Str::afterLast($e->getMessage(), 'description":"');
            $error = Str::before($error, '"');
            throw new \Exception($error, $e->getCode());
        }
    }
}
