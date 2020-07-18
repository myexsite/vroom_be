<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ChangePasswordRequest;
use Symfony\Component\HttpFoundation\Response;

class ResetPasswordController extends Controller
{
    public function send_email(Request $request)
    {
        $email = $request->input('email');

        // if ($this->validate_email($request->email)) {
        if (!$this->validate_email($email)) {
            return $this->failedResponse();
        }

        $this->send($request->email);
        return $this->successResponse();
    }

    public function send($email)
    {
        $token = $this->createToken($email);
        Mail::to($email)->send(new ResetPasswordMail($token));
    }

    public function createToken($email)
    {
        //  $subject_name = Subject::select('subject')->where('id', $id)->first();
        $old_token = DB::table('password_resets')->select('token')->where('email', $email)->first();
        if ($old_token) {
            return $old_token->token;
        } else  {
            // $token = str_random(60);
            // $token = str_random(6);
            $token = Str::random(6);
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


    public function validate_email($email)
    {
        return !!User::where('email', $email)->first();
        // return User::where('email', $email)->first();
        // return DB::table('users')->where('email', '=', $email)->get();
        // return $email;
    }

    public function failedResponse()
    {
        return response()->json([
            'error' => 'Email does not exist'
        ], Response::HTTP_NOT_FOUND);
    }
    public function successResponse()
    {
        return response()->json([
            'data' => 'Reset Email sent successfully'
        ], Response::HTTP_OK);
    }

    public function change_password(ChangePasswordRequest $request)
    {
        return $this->getPasswordRequestRow($request)->count() > 0 ? $this->change($request) : $this->tokenNotFoundResponse();
    }

    private function getPasswordRequestRow($request)
    {
        return DB::table('password_resets')->where([
            'email' => $request->email,
            'token' => $request->resetToken
        ]);
    }

    private function tokenNotFoundResponse()
    {
        return response()->json(['error' => 'Token or Email Incorrect'], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    private function change($request)
    {
        $user = User::whereEmail($request->email)->first();
        $user->update([
            'password' => $request->password
        ]);
        $this->getPasswordRequestRow($request)->delete();
        return response()->json(['data' => 'Password Successfully Changed'], Response::HTTP_CREATED);
    }
}
