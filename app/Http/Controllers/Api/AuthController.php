<?php
namespace App\Http\Controllers\Api;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
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

    public function me(Request $request)
    {
        $token = $request->header('Authorization');
        
        $user = User::select('email', 'token', 'secret')->where('token', $token)->first();
        
        if ( !is_null($user) && 1 === $user->count() ) {
              return response()->json($user);
        } else {
            return response()->json(['err' => true]);
        }
    }

    public function logout()
    {
        
        User::where('email', $user)
            ->update(['token' => null]);

        return response()->json(['logout' => '1']);
    }

}
