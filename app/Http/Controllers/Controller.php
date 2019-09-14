<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Config;

use Tymon\JWTAuth\Facades\JWTAuth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function getCurrentUser()
    {
        return JWTAuth::parseToken()->authenticate();
    }

    protected function getUserRoleInstance()
    {
        $user = JWTAuth::parseToken()->authenticate();

        switch($user->role_id)
        {
            case Config::get('constants.roles.organization'):
                return $user->organization;
                break;
            case Config::get('constants.roles.socc'):
                return $user->socc;
                break;
            case Config::get('constants.roles.osa'):
                return $user->osa;
                break;
        }
    }
}
