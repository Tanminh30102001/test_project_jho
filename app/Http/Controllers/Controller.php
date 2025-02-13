<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    protected function successResponse($data = [], $message = "Success", $status = 200): JsonResponse
    {
        return response()->json([
            'status'=>$status,
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $status);
    }
    protected function errorResponse($message = "Error", $status = 400, $errors = []): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $status);
    }
}
