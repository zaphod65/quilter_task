<?php
     
namespace App\Http\Controllers;
     
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Validator;
     
class RegisterController
{
    public function register(RegisterUserRequest $request): JsonResponse
    {
        $input = $request->only([
            'name', 'email', 'password',
        ]);
        
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('quilter_task')->accessToken;
        $success['name'] =  $user->name;
   
        return response()->json($success, 200);
    }
}