<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiTraits;

    public function socialLogin(LoginRequest $request)
    {
        try {
            $userExist = User::where('oauth_id', $request->id)->where("oauth_type", $request->type)->first();
            if(!$userExist){
                $userExist = User::where('email',$request->email)->first();
                if($userExist){
                    $userExist->update([
                        'oauth_id' => $request->type,
                        'oauth_type' => $request->type,
                    ]);
                }else{
                    $userExist = User::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'oauth_id' => $request->id,
                        'oauth_type' => $request->type,
                        'password' => Hash::make($request->id),
                    ]);
                }  
            }
            Auth::login(($userExist));
            $apiToke  = $userExist->createToken('auth_token')->accessToken;
            $userExist->api_token = $apiToke;
            return $this->responseJson(new UserResource($userExist), 'Login Successfully');
        } catch (\Throwable $e) {
            return $this->responseJsonFailed();
        }

    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();
            $user->token()->delete();
            $user->delete();
            return $this->responseJsonWithoutData();
        } catch (\Throwable $th) {
            return $this->responseJsonFailed();
        }
    }

    public function logoutAllDevices(Request $request)
    {
        try {
            $user = $request->user();
            $user->tokens->each(function($token) {
                $token->delete();
            });
            return $this->responseJsonWithoutData();
        } catch (\Throwable $th) {
            return $this->responseJsonFailed();
        }
    }
}
