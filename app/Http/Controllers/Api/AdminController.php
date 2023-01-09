<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Auth;
use Validator;

class AdminController extends Controller
{
    public $successStatus = 200;

    /**
     * login api
     *
     * @return JsonResponse
     */
    public function login()
    {
        if (Auth::attempt(
            [
                'email' => request('email'),
                'password' => request('password')
            ]
        )) {
            $admin = Auth::user();
            $success['token'] = $admin->createToken($admin->name)->accessToken;

            return response()->json(
                [
                    'success' => $success
                ],
                $this->successStatus
            );
        }
        else {
            return response()->json(
                [
                    'error' => 'Unauthorised'
                ], 401);
        }
    }

    /**
     * Register api
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request)
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
            return response()->json(
                [
                    'error' => $validator->errors()
                ], 401);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $admin = Admin::create($input);
        $success['token'] = $admin->createToken('MyApp')->accessToken;
        $success['name'] = $admin->name;

        return response()->json(
            [
                'success' => $success
            ],
            $this->successStatus
        );
    }

    /**
     * details api
     *
     * @return JsonResponse
     */
    public function details()
    {
        $admin = Auth::user();

        return response()->json(
            [
                'success' => $admin
            ],
            $this->successStatus
        );
    }
}
