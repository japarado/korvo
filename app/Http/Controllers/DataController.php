<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Tymon\JWTAuth\Facades\JWTAuth;

class DataController extends Controller
{

    public function open()
    {
        $data = "This data is open and can be accessed without the client 
            being authenticated";
        return response()->json([
            'message' => 'Accessing a non-JWT protected route'
        ]);
    }

    public function closed()
    {
        $data = "Only authorized users can see this";
        return response()->json(compact('data'), 200);
    }

    public function test(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        return $user;
        /* $token = $request->header('Authorization'); */
        /* $user = JWTAuth::user(); */
        /* $role = Config::get('roles.organization'); */
        /* return response()->json([ */
        /*     'token' => $request->header('Authorization'), */
        /*     'user' => $user, */
        /*     'role' */
        /* ]); */
    }
}
