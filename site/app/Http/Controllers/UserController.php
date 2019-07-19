<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Firebase\JWT\JWT;
use App\User;
use Carbon\Carbon;

class UserController extends Controller
{
    protected function jwt(User $user) {
       $payload = [
            'iss' => "user-token", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => Carbon::now()->timestamp, // Time when JWT was issued. 
            'exp' => Carbon::now()->addMinutes(60)->timestamp // Expiration time
        ];

       return JWT::encode($payload, env('JWT_SECRET'));
    }

    public function login(Request $request) 
    {
        $username_type = filter_var($request->input('username'), FILTER_VALIDATE_EMAIL) ? 'email' : 'telno';

        if ($username_type === 'email') {
            $user = User::whereEmail($request->input('username'))->first();
        } else if ($username_type === 'telno') {
            $user = User::whereTelno($request->input('username'))->first();
        }
        
        if (!empty($user)) {
            if (Hash::check($request->input('password'), $user->password)) {
                $jwt = $this->jwt($user);
                $jwt_payload = JWT::decode($jwt, env('JWT_SECRET'), ['HS256']);
    
                return response()->json([
                    'status'  => 200,
                    'message' => 'Login Successful',
                    'data'    => [
                        'token_type' => 'Bearer',
                        'access_token' => $jwt,
                        'expires_at' => $jwt_payload->exp 
                    ]
                ], 200);
            } else {
                return response()->json([
                    'status'  => 404,
                    'message' => 'Password Incorect !', // return token
                ], 404);
            }
        } else {
            return response()->json([
                'status'  => 404,
                'message' => 'User Not Found', // return token
            ], 404);
        }

    }

    public function getProfile(Request $request) 
    {
        $user = $request->auth;
        return [
            'id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'telno' => $user->telno,
            'email' => $user->email,
            'thumbnail_url' => $user->thumbnail_url,
            'gender' => $user->gender
        ];
    }

    public function createUser(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|unique:users',
            'password' => 'required'
        ]);
        $user = [];
        $username_type = filter_var($request->input('username'), FILTER_VALIDATE_EMAIL) ? 'email' : 'telno';
        $name = ($username_type === 'email') ? explode('@', $request->input('username'))[0] : $request->input('username');
        $user = User::create([
            'name' => $name,
            'username' => $request->input('username'),
            'password' => Hash::make($request->input('password')),
            'telno' => ($username_type === 'telno') ? $request->input('username') : null,
            'email' => ($username_type === 'email') ? $request->input('username') : null,
            'gender' => null,
            'thumbnail' => null,
            'telno_verify' => false,
            'email_verify' => false,
        ]);

        return response()->json([
            'message' => 'Register success'
        ], 200);
    }

    public function destroyUser(Request $request, $id)
    {
        $user = User::find($id);

        if ($user) {
            if ($user->delete()) {
                return response()->json([
                    'message' => 'Delete success'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Delete unsuccess'
                ], 404);
            }
        } else {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }
    }

    public function updateUser(Request $request)
    {
        $user_id = $request->auth->id;
        $user = User::whereId($user_id)->first();
        if (!empty($user)) {
            if ($user->email === $request->input('email')) {
                return response()->json([
                    'message' => 'Email has already'
                ], 400);
            }
            if ($user->telno === $request->input('telno')) {
                return response()->json([
                    'message' => 'Telno has already'
                ], 400);
            }
            $user->update([
                'name' => $request->input('name') ? $request->input('name') : $user->name,
                'telno' => $request->input('telno') ? $request->input('telno') : $user->telno,
                'email' => $request->input('email') ? $request->input('email') : $user->email,
                'gender' => $request->input('gender') ? $request->input('gender') : $user->gender
            ]);
            if ($request->hasFile('thumbnail')) {
                $filename = sprintf('thumbnail/%s/%s.jpg', date('Y/m/d'), str_random(8));
                $disk = Storage::disk('public');
                $image = Image::make($request->file('thumbnail')->path())->encode('jpg', 75);
                if ($disk->exists($user->thumbnail)) {
                    $disk->delete($user->thumbnail);
                }
                if ($disk->put($filename, $image)) {
                    $user->update(['thumbnail' => $filename]);
                }
            }

            return response()->json([
                'message' => 'Update success'
            ], 200);
        }
    }

    public function testUpload(Request $request)
    {
        if ($request->hasFile('image')) {
            $filename = sprintf('thumbnail/%s/%s.jpg', date('Y/m/d'), str_random(8));
            $disk = Storage::disk('public');
            $image = Image::make($request->file('image')->path())->fit(150, 150)->encode('jpg', 75);
            if ($disk->put($filename, $image)) {
                return response()->json([
                    'message' => 'uploaded success'
                ], 200);
            }
        } else {
            return [];
        }
    }
}
