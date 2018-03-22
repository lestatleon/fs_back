<?php
namespace App\Http\Controllers\Api;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // $auth = $request->header('Authorization');
        // $auth = str_replace('Basic ','', $auth);
        // $auth = base64_decode($auth);
        // $user = explode(':', $auth);

        $user = $request->get('user', null); 

        $user['password'] = hash('sha1', $user['password']);

        $exist = User::where('email', $user['email'])
            ->where('secret', $user['password'])->count();
        if ( 1 === $exist ) {
            $token = hash('md5', $user['password'] . date('Y-m-d H:m:s'));

            User::where('email', $user['email'])
            ->update(['token' => $token]);

            $user = [ 'email' => $user['email'], 'token' => $token ];
        } else {
            $user = false;
        }

        return response()->json($user);
    }

    public function checkToken()
    {
        $email = $request->get('email', null);
        $token = $request->get('token', null);

        $exist = User::where('email', $email)
            ->where('token', $token)->count();
        
        if ( 1 === $exist ) {
            return response()->json(['logged' => '1']);
        } else {
            return response()->json(['logged' => '0']);
        }
    }

    public function logout()
    {
        
        User::where('email', $user)
            ->update(['token' => null]);

        return response()->json(['logout' => '1']);
    }

}
