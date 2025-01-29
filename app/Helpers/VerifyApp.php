<?php

namespace App\Helpers;

use App\Models\Application;
use App\Models\User;


class VerifyApp
{
    /**
     * @param $resource_class
     * @param $record_id
     * @return mixed
     */
    public function checkStatusApp($version, $platform)
    {
        $last_app = $this->searchApp($version, $platform);

        return !empty($last_app) && $last_app->enable == True ? True : False;
    }


    public function syncNewSession($userData)
    {

        $app = $this->searchApp($userData['version'], $userData['platform']);
        $user = User::findOrFail(auth()->user()->id);
        $user->applications()->syncWithPivotValues(
            [$app->id],
            [
                'platform_version' => $userData['platform_version'],
                'last_session' => now()
            ]
        );
    }

    private function searchApp($version, $platform)
    {
        return Application::where('version', $version)
            ->where('platform', $platform)->orderByDesc('version')->first();
    }
}
