<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class OdooHelper
{

    protected $from, $client, $DB, $URL;

    public function __construct($from)
    {   
        $this->DB = $from == 'autobox' 
        ? $this->getDataBase(env('ODOO_AUTOBOX_URL')) 
        : $this->getDataBase(env('ODOO_GWMVE_URL'));

        $this->URL = $from == 'autobox' 
        ? env('ODOO_AUTOBOX_URL')
        : env('ODOO_GWMVE_URL');

        $this->client = new Client(['cookies' => true]);
    }

    protected function AuthOdoo()
    {   
        $credentials = [
            'db' => $this->DB,
            'login' => env('ODOO_USER'),
            'password' => env('ODOO_PASSWORD')
        ];

        $json_body = [
            'jsonrpc' => '2.0',
            'params'  => $credentials
        ];

        return $this->client->request('POST', $this->URL . 'web/session/authenticate', [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode($json_body),
        ]);
    }

    public function getServices($params)
    {
       
        $this->AuthOdoo();
        $data = $this->client->request('POST', $this->URL . 'v2/services', [
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
        $data = $this->client->request('POST', $this->URL . 'v2/processed', [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode($params)
        ]);

        $response = json_decode($data->getBody());

        Log::info("---- Have been updated in odoo {$response->result} items ----");
    }

    private function getDataBase($url){
        $urlWithoutProtocol = preg_replace('/^https:\/\//', '', $url);
        $cleanedUrl = preg_replace('/\.dev\.odoo\.com\/$/', '', $urlWithoutProtocol);
        
        return $cleanedUrl;
    }
}
