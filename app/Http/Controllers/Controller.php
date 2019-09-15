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

    protected static function getCurrentUser()
    {
        return JWTAuth::parseToken()->authenticate();
    }

    protected static function getUserRoleInstance($user = null)
    {
        $user = $user ?: JWTAuth::parseToken()->authenticate();

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

    protected static function castUserToRole($user)
    {
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

    public static function isOrgUser()
    {
        $user = JWTAuth::parseToken()->authenticate();
        return $user->role_id == Config::get('constants.roles.organization');
    }

    public static function isSoccUser()
    {
        $user = JWTAuth::parseToken()->authenticate();
        return $user->role_id == Config::get('constants.roles.socc');
    }

    public static function isOsaUser()
    {
        $user = JWTAuth::parseToken()->authenticate();
        return $user->role_id == Config::get('constants.roles.osa');
    }
}
