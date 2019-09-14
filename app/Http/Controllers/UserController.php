<?php

namespace App\Http\Controllers;

use App\User;
use App\Organization;
use App\SOCC;
use App\OSA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Config;

class UserController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try
        {
            if(!$token = JWTAuth::attempt($credentials))
            {
                return response()->json([
                    'error' => 'invalid_credentials'
                ], 400);
            }
        }
        catch(JWTException $e)
        {
            return response()->json([
                'error' => 'could_not_create_token'
            ], 500);
        }
        return response()->json(compact('token'));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        /* $user = User::create([ */
        /*     'name' => $request->get('name'), */
        /*     'email' => $request->get('email'), */
        /*     'password' => Hash::make($request->get('password')), */
        /* ]); */

        $user = static::createUser($request);
        $user->save();
        $sub_user = static::createSubUser($request, $user->id);
        $sub_user->save();

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user','token'),201);
    }

    private static function createUser(Request $request)
    {
        $user = new User();
        $user->email = $request->get('email');
        $user->first_name = $request->get('first_name');
        $user->last_name = $request->get('last_name');
        $user->password = Hash::make($request->get('password'));
        $user->student_number = $request->get('student_number') ?: null;
        $user->role_id = $request->get('role_id');
        return $user;
    }

    private static function createSubUser(Request $request, $user_id)
    {
        $role_id = $request->get('role_id');
        if($role_id == 1)
        {
            $sub_user = new Organization();
            $sub_user->user_id = $user_id;
            $sub_user->name = $request->get('organization_name');
            $sub_user->type = $request->get('organization_type');
            $sub_user->college = $request->get('organization_college');
        }
        elseif($role_id == 2)
        {
            $sub_user = new SOCC();
            $sub_user->user_id = $user_id;
        }
        elseif($role_id == 3)
        {
            $sub_user = new OSA();
            $sub_user->user_id = $user_id;
        }
        return $sub_user;
        /* switch($request->get('role_id')) */
        /* { */
        /*     case (int)Config::get('constants.roles.organization'): */
        /*         $sub_user = new Organization(); */
        /*         $sub_user->user_id = $user_id; */
        /*         $sub_user->name = $request->get('organization_name'); */
        /*         $sub_user->type = $request->get('organization_type'); */
        /*         $sub_user->college = $request->get('organization_college'); */
        /*         $sub_user->save(); */
        /*         break; */
        /*     case (int)Config::get('constants.roles.socc'): */
        /*         $sub_user = new SOCC(); */
        /*         $sub_user->user_id = $user_id; */
        /*         $sub_user->save(); */
        /*         break; */
        /*     case (int)Config::get('constants.roles.osa'): */
        /*         $sub_user = new OSA(); */
        /*         $sub_user->user_id = $user_id; */
        /*         $sub_user->save(); */
        /*         break; */
        /* } */
    }

    public function getAuthenticatedUser()
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        return response()->json(compact('user'));
    }    

    // Helper Functions


}
