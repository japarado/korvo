<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class DataController extends Controller
{
    public function open()
    {
        $data = "This data is open and can be accessed without the client 
            being authenticated";
        return response()->json(compact('data'), 200);
    }

    public function closed()
    {
        $data = "Only authorized users can see this";
        return response()->json(compact('data'), 200);
    }

    public function test(Request $request)
    {
        $token = $request->header('Authorization');
        $user = JWTAuth::user();
        return response()->json([
            'token' => $request->header('Authorization'),
            'user' => $user,
        ]);
    }
}
