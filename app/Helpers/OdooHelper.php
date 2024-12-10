<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class OdooHelper
{

    protected $credentials, $client;

    public function __construct()
    {

        $this->credentials = [
            'db' => env('ODOO_DB'),
            'login' => env('ODOO_USER'),
            'password' => env('ODOO_PASSWORD')
        ];

        $this->client = new Client(['cookies' => true]);
    }

    protected function AuthOdoo()
    {
        $json_body = [
            'jsonrpc' => '2.0',
            'params'  => $this->credentials
        ];

        return $this->client->request('POST', env('ODOO_URL') . 'web/session/authenticate', [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode($json_body),
        ]);
    }

    public function getServices($params)
    {

        $this->AuthOdoo();

        $data = $this->client->request('POST', env('ODOO_URL') . 'v2/services', [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode($params)
        ]);

        $response = json_decode($data->getBody());

        if (!$response->result) {
            response()->json(['errors' => 'Whoops, ha ocurrido un problema intentando acceder a la informacion'], 400);
        }

        return $response->result;
    }

    public function sendProcess($params)
    {   

        $this->AuthOdoo();
        $data = $this->client->request('POST', env('ODOO_URL') . 'v2/processed', [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode($params)
        ]);

        $response = json_decode($data->getBody());
        Log::info("---- Have been updated in odoo {$response->result} items ----");
    }   
}
