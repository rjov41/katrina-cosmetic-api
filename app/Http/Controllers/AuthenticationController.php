<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    //this method adds new users
    public function createAccount(Request $request)
    {
        $attr = $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            'email' => 'required|string|email|unique:users,email',
            'apellido' => 'required|string|max:255',
            'cargo' => 'required|string|max:255',
            'estado' => 'required|numeric|max:1',
        ]);

        $user = User::create([
            'name' => $attr['name'],
            'password' => bcrypt($attr['password']),
            'email' => $attr['email'],
            'apellido' => $attr['apellido'],
            'cargo' => $attr['cargo'],
            'estado' => $attr['estado']
        ]);
        
        $user->assignRole('admin');
        
        // return ['token' => $user->createToken('tokens')->plainTextToken];
        return response()->json(['token' => $user->createToken('tokens')->plainTextToken], 201);

        // return $this->success([
        //     'token' => $user->createToken('tokens')->plainTextToken
        // ]);
    }
    
    //use this method to signin users
    public function signin(Request $request)
    {
        $attr = $request->validate([
            'email' => 'required|string|email|',
            'password' => 'required|string|min:6'
        ]);

        if (!Auth::attempt($attr)) {
            // return $this->error('Credentials not match', 401);
            return ['error' => "problemas"];
        }
        
        $user = Auth::user();
        if ($user instanceof \App\Models\User) {
            // Hinting here for $user will be specific to the User object
            // return $this->success([
            //     'token' => $user->createToken($request->device_name)->plainTextToken,
            // ]);
            return ['token' => $user->createToken('tokens')->plainTextToken];
        } else {
            // return $this->error('Error', 401);
            return ['error' => "problemas"];
        }
        

    }

    // this method signs out users by removing tokens
    public function signout(Request $request)
    {
        // dd($request->user()->currentAccessToken());
        $user = Auth::user();
        if ($user instanceof \App\Models\User) {
            $user->tokens()->delete();
        } else {
            return ['error' => "problemas"];
        }

        return [
            'message' => 'Tokens Revoked'
        ];
    }
}
