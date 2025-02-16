<?php

namespace App\Http\Controllers\Apis;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    /**
     * @OA\Info(
     *     title="Translation Management System APIs",
     *     version="1.0.0",
     *     description="Your API Description",
     *     @OA\Contact(
     *         email="nahyan.bin.khalid@gmail.com",
     *         name="API Support"
     *     ),
     *     @OA\License(
     *         name="Nahyan",
     *         url="nahyan.bin.khlid@gmail.com"
     *     ),
     * ),
     *
     * @OA\SecurityScheme(
     *     securityScheme="bearerAuth",
     *     type="http",
     *     scheme="bearer",
     *     bearerFormat="Bearer {token}"
     * ),
     */

    public function successResponse($data, $message = null, $code = 200)
    {
        return response()->json([
            'error'     =>  false,
            'message'   =>  (!is_null($message)) ? $message : "Process Successful",
            'data'      =>  $data
        ], $code);
    }

    public function errorResponse($message = null, $code = 500)
    {
        return response()->json([
            'error'     =>  true,
            'message'   =>  $message ?? 'Bad Request',
            'data'      =>  null
        ], $code);
    }

    public function sendResponse($error = false, $code = 200, $message = null, $data = null)
    {
        return response()->json([
            'error'     =>  $error,
            'message'   =>  (!is_null($message)) ? $message : "Process Successful",
            'data'      =>  $data
        ], $code > 0 ? $code : 400);
    }
}
