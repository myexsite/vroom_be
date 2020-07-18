<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\Signup;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    function index(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        // print_r($data);
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response([
                    'message' => ['These credentials do not match our records.']
                ], 404);
            }
        
             $token = $user->createToken('my-app-token')->plainTextToken;
        
            $response = [
                'user' => $user,
                'token' => $token
            ];
        
             return response($response, 201);
            // return $this->respondWithToken($user->createAccessToken(), ["user" => $user]);
    }

    function signup(Signup $request) {
        $user_type = $request->input('user_type');
        $email = $request->input('email');
        $user = User::create($request->all());
        // return response()->json(['message' => 'user added']);
        // return $user;
        return $this->index($request);
    }
    
    public function createToken($email)
    {
        //  $subject_name = Subject::select('subject')->where('id', $id)->first();
        $old_token = DB::table('password_resets')->select('token')->where('email', $email)->first();
        if ($old_token) {
            return $old_token->token;
        } else  {
            // $token = str_random(60);
            $token = str_random(6);
            $this->saveToken($token, $email);
            return $token;
        }
        
    }

    public function saveToken($token, $email)
    {
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
    }

    function users() {
        return User::all();
    }
}
