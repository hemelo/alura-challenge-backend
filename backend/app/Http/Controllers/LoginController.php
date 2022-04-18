<?php

namespace App\Http\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->first();
        
        try {
            $decrypted = Crypt::decrypt($user->password);
            if($decrypted != $password) throw new DecryptException();
            $token = JWT::encode(['nome' => $user->name, 'email' => $user->email], env('JWT_KEY'), 'HS256');
          
            return response()->json(['token' => $token], 200);

        } catch (DecryptException $e) {
            return response()->json(['error', 'Incorrect password'], 401);
        }
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

    

        try 
        {
            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $plainPassword = $request->input('password');
            $user->password = Crypt::encrypt($plainPassword);

            $user->save();

            //return successful response
            return response()->json(['message' => 'User has been created with success.'], 201);

        } 
        catch (\Exception $e) 
        {
            return response()->json(['message' => 'User registration failed!'], 409);
        }

    }
}
