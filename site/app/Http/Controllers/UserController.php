<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;
use App\User;

class UserController extends Controller
{
    protected function jwt(User $user) {
        $payload = [
           'iss' => "lumen-jwt", // Issuer of the token
           'sub' => $user->id, // Subject of the token
           'iat' => time(), // Time when JWT was issued. 
           'exp' => time() + 60*60 // Expiration time
       ];
       // be used to decode the token in the future.
       
       return JWT::encode($payload, env('JWT_SECRET'));
    }

    public function login(Request $request) 
    {
        $username_type = filter_var($request->input('username'), FILTER_VALIDATE_EMAIL) ? 'email' : 'telno';
        if ($username_type === 'email') {
            $user = User::whereEmail($request->input('username'))->firstOrfail();
        } else if ($username_type === 'telno') {
            $user = User::whereTelno($request->input('username'))->firstOrfail();
        }
        if (Hash::check($request->input('password'), $user->password)) {
            return response()->json([
                'status'  => 200,
                'message' => 'Login Successful',
                'data'    => ['token' => $this->jwt($user) ] // return token
            ], 200);
        } else {
            return response()->json([
                'status'  => 404,
                'message' => 'Password Incorect !', // return token
            ], 404);
        }
    }

    public function getProfile(Request $request) 
    {
        $user = $request->auth;

        return [
            'id' => $user->id,
            'name' => $user->name,
            'thumbnail' => $user->thumbnail,
            'gender' => $user->gender
        ];
    }

    public function createUser(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        $user = [];
        $username_type = filter_var($request->input('username'), FILTER_VALIDATE_EMAIL) ? 'email' : 'telno';
        $hash = Hash::make($request->input('password'));
        $name = ($username_type === 'email') ? explode('@', $request->input('username'))[0] : $request->input('username');
        $user = User::firstOrCreate([
            'name' => $name,
            'password' => $hash,
            'telno' => ($username_type === 'telno') ? $request->input('username') : null,
            'email' => ($username_type === 'email') ? $request->input('username') : null,
            'gender' => null,
            'thumbnail' => null
        ]);

        return $user;
    }

    public function destroyUser(Request $request, $id)
    {
        $user = User::find($id);

        if ($user) {
            if ($user->delete()) {
                return response()->json([
                    'message' => 'delete success'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'delete unsuccess'
                ], 404);
            }
        } else {
            return response()->json([
                'message' => 'user not found'
            ], 404);
        }
    }

    public function updateUser(Request $request)
    {
        $user_id = $request->auth->id;
        $user = User::whereId($user_id)->update($request->only(['name', 'telno', 'email', 'gender', 'thumbnail']));
        if ($user) {
            return response()->json([
                'message' => 'update success'
            ], 200);
        }
    }
}
