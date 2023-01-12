<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param array $data
     * @param string $msg
     * @return JsonResponse
     */
    public function responseSuccess($data = [], $msg = 'Successful!'): JsonResponse
    {
        return response()->json([
            'status' => SUCCESS,
            'message' => $msg,
            'results' => $data
        ], SUCCESS);
    }

    /**
     * @param array $errors
     * @param string $msg
     * @return JsonResponse
     */
    public function responseInvalid($errors = [], $msg = "Error: Request is invalid!"): JsonResponse
    {
        return response()->json([
            'status' => VALIDATOR,
            'message' => $msg,
            'errors' => $errors
        ], VALIDATOR);
    }

    /**
     * @param string $msg
     * @return JsonResponse
     */
    public function responseServerError($msg = API_ERROR): JsonResponse
    {
        return response()->json([
            'status' => SEVER_ERROR,
            'message' => $msg
        ], SEVER_ERROR);
    }

    /**
     * @param string $msg
     * @return JsonResponse
     */
    public function responseNotFound($msg = "Not Found!"): JsonResponse
    {
        return response()->json([
            'status' => NOTFOUND,
            'message' => $msg
        ], NOTFOUND);
    }

    /**
     * @param string $msg
     * @return JsonResponse
     */
    public function responseUnauthorised($msg = "Unauthorised!"): JsonResponse
    {
        return response()->json([
            'status' => UNAUTHORIZED,
            'message' => $msg
        ], UNAUTHORIZED);
    }
}
