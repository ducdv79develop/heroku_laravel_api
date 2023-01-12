<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * RegisterController constructor.
     */
    public function __construct()
    {

    }

    /**
     * Register api
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(),
            [
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'c_password' => 'required|same:password',
            ]
        );

        if ($validator->fails()) {
            return $this->responseInvalid($validator->errors());
        }

        try {
            $params = $request->only(['name', 'email']);
            $params['password'] = Hash::make($request->get('password'));
            $admin = Admin::create($params);
            $accessToken = $admin->createToken($admin->name)->accessToken;

            return $this->responseSuccess([
                'token' => $accessToken,
                'name' => $admin->name,
                'email' => $admin->email,
            ]);

        } catch (Exception $exception) {
            return $this->responseServerError();
        }
    }
}
