<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    /**
     * AdminController constructor.
     */
    public function __construct()
    {

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function listMember(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'keyword' => 'nullable|max:50',
            'page' => 'nullable|int',
            'per_page' => 'nullable|int|max:1000',
        ]);

        if ($validator->fails()) {
            return $this->responseInvalid($validator->errors());
        }

        try {
            $perPage = $request->get('per_page', 20);
            $condition = Admin::query()
                ->select('id', 'name', 'email', 'created_at', 'updated_at')
                ->orderBy('id', 'ASC');
            if ($request->has('keyword')) {
                $keyword = $request->get('keyword');
                $condition->where('name', 'LIKE', "%$keyword%")
                    ->orWhere('email', 'LIKE', "%$keyword%");
            }

            $admins = $condition->paginate($perPage);
            return $this->responseSuccess($admins->toArray());

        } catch (Exception $exception) {
            return $this->responseServerError();
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function showMember(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|int',
        ]);

        if ($validator->fails()) {
            return $this->responseInvalid($validator->errors());
        }

        try {
            $admin = Admin::query()
                ->where('id', $request->get('id'))
                ->select('id', 'name', 'email', 'created_at', 'updated_at')
                ->first();

            if (empty($admin)) {
                return $this->responseNotFound();
            }

            return $this->responseSuccess($admin->toArray());

        } catch (Exception $exception) {
            return $this->responseServerError();
        }
    }
}
