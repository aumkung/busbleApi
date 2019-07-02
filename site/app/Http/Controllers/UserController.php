<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function login(Request $request) 
    {
        return [
            'api' => 'lumen 5.8',
            'company' => 'busble',
            'username' => $request->input('username'),
            'password' => $request->input('password')
        ];
    }
    public function getUser(Request $request, $id)
    {
        return [
            'api' => 'lumen 5.8',
            'company' => 'busble',
            'username' => $request->input('username'),
            'password' => $request->input('password')
        ];
    }

    public function createUser(Request $request)
    {
        return [
            'api' => 'lumen 5.8',
            'company' => 'busble',
            'username' => $request->input('username'),
            'password' => $request->input('password')
        ];
    }

    public function destroyUser(Request $request, $id)
    {
        return [
            'api' => 'lumen 5.8',
            'company' => 'busble',
            'username' => $request->input('username'),
            'password' => $request->input('password')
        ];
    }

    public function updateUser(Request $request, $id)
    {
        return [
            'api' => 'lumen 5.8',
            'company' => 'busble',
            'username' => $request->input('username'),
            'password' => $request->input('password')
        ];
    }
}
