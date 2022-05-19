<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Traits\ApiTraits;

class UserController extends Controller
{
    use ApiTraits;
    
    public function updateProfile(UpdateUserRequest $request)
    {
        try {
            $user = $request->user();
            $user->update($request->all());
            return $this->responseJson(new UserResource($user), 'Profile Updated Successfully');
        } catch (\Throwable $th) {
            return $this->responseJsonFailed();
        }
    }

    public function deleteProfile(UpdateUserRequest $request)
    {
        try {
            $user = $request->user();
            $user->revokeTokens($user->tokens);
            $user->delete();
            return $this->responseJsonWithoutData();
        } catch (\Throwable $th) {
            return $this->responseJsonFailed();
        }
    }

}
