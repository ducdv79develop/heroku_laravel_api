<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    private $guard = 'web';

    /**
     * LoginController constructor.
     */
    public function __construct()
    {

    }

    /**
     * @return Guard|StatefulGuard
     */
    public function guard()
    {
        return Auth::guard($this->guard);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseInvalid($validator->errors());
        }

        $credentials = [
            'email' => $request->get('email'),
            'password' => $request->get('password')
        ];

        if ($this->guard()->attempt($credentials)) {
            $admin = $this->guard()->user();
            $accessToken = $admin->createToken($admin->name)->accessToken;

            return $this->responseSuccess([
                'token' => $accessToken,
                'name' => $admin->name,
                'email' => $admin->email,
            ]);
        } else {
            return $this->responseUnauthorised();
        }
    }
}
