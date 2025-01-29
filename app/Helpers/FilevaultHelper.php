<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;


class FilevaultHelper
{

    protected function authFileVault(){
        return Http::post(Config::get('filevault.host').'api/auth/generate_token', [
            'code' => Config::get('filevault.code'),
            'secret_key' => Config::get('filevault.secret_key'),
        ])->json()['token'];
    }

    public function uploadFile($file){

        $response = Http::attach(
            'file',
            file_get_contents($file->getRealPath()),
            $file->getClientOriginalName()
        )->post(
            Config::get('filevault.host') . 'api/files/upload',
            [
                'token' => $this->authFileVault(),
                'type_id' => Config::get('filevault.type_id'),
                'user_id' => auth()->user()->id
            ]
        );

        if ($response->failed()) {
            Log::error('File upload failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return [
                'success' => false,
                'message' => 'File upload failed. Please try again later.',
                'details' => $response->json()
            ];
        }

        return [
            'success' => true,
            'data' => $response->json()
        ];
}

    public function removeFile($file_path){

        $response = Http::asForm()->post(Config::get('filevault.host') . 'api/files/remove', [
            'token' => $this->authFileVault(),
            'file_path' => $file_path,
            'user_id' => auth()->user()->id
        ]);

        $response->json();
    }
}