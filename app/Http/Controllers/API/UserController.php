<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;


class UserController extends BaseController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('accessToken')->accessToken;
        $success['name'] = $user->name;
        return $this->sendResponse($success, 'User register successfully.');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails())
            return $this->sendError('Validation Error.', $validator->errors());

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $success['name'] = $user->name;
            $success['token'] = $user->createToken('accessToken')->accessToken;

            return $this->sendResponse($success, 'You are successfully logged in.');
        } else {
            return $this->sendError('Unauthorised', ['error' => 'Unauthorised']);
        }
    }
}
