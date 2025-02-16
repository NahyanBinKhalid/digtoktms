<?php

namespace App\Http\Controllers\Apis\V1;

use App\Http\Controllers\Apis\ApiController;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ForgotRequest;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetRequest;
use App\Http\Requests\UploadRequest;
use App\Repositories\Interfaces\AuthInterface;

class AuthController extends ApiController
{
    /**
     * @OA\Post(
     *     path="/api/v1/register",
     *     summary="Register a user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"name", "email", "password", "password_confirmation"},
     *                 @OA\Property(property="name", type="string", example="Doe"),
     *                 @OA\Property(property="email", type="string", format="email", example="doe@example.com"),
     *                 @OA\Property(property="password", type="string", example="password"),
     *                 @OA\Property(property="password_confirmation", type="string", example="password"),
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="User Registered Successfully"),
     *     @OA\Response(response=401, description="Data Incomplete or Invalid"),
     *     @OA\Response(response=422, description="Validation Error"),
     *     @OA\Response(response=500, description="Internal Server Error"),
     * )
     */
    public function register(RegisterRequest $request, AuthInterface $auth)
    {
        $registeredUser = $auth->register($request->except('password_confirmation'));
        return $this->sendResponse($registeredUser['error'], $registeredUser['code'], $registeredUser['message'], $registeredUser['data']);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/login",
     *     summary="Login a User",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"email", "password"},
     *                 @OA\Property(property="email", type="string", format="email", example="onolan@example.com"),
     *                 @OA\Property(property="password", type="string", example="password"),
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="User Registered Successfully"),
     *     @OA\Response(response=401, description="Data Incomplete or Invalid"),
     *     @OA\Response(response=422, description="Validation Error"),
     *     @OA\Response(response=500, description="Internal Server Error"),
     * )
     */
    public function login(LoginRequest $request, AuthInterface $auth)
    {
        $authUser = $auth->login($request->only('email', 'password'));
        return $this->sendResponse($authUser['error'], $authUser['code'], $authUser['message'], $authUser['data']);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/logout",
     *     tags={"Authentication"},
     *     summary="Logout from Profile",
     *     @OA\Response(response=200, description="Profile Details"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=500, description="Internal Server Error"),
     *
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function logout(AuthInterface $auth)
    {
        $authUser = $auth->logout();
        return $this->sendResponse($authUser['error'], $authUser['code'], $authUser['message'], $authUser['data']);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/profile",
     *     tags={"Authentication"},
     *     summary="Logout from Profile",
     *     @OA\Response(response=200, description="Profile Details"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=500, description="Internal Server Error"),
     *
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function profile(AuthInterface $auth)
    {
        $authUser = $auth->profile();
        return $this->sendResponse($authUser['error'], $authUser['code'], $authUser['message'], $authUser['data']);
    }
    
    /**
     * @OA\Put(
     *     path="/api/v1/profile",
     *     summary="Update Profile",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"name", "email"},
     *                 @OA\Property(property="name", type="string", example="Doe"),
     *                 @OA\Property(property="email", type="string", format="email", example="onolan@example.com")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="User Profile Updated Successfully"),
     *     @OA\Response(response=401, description="Data Incomplete or Invalid"),
     *     @OA\Response(response=422, description="Validation Error"),
     *     @OA\Response(response=500, description="Internal Server Error"),
     * 
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function update(ProfileRequest $request, AuthInterface $auth)
    {
        $registeredUser = $auth->update($request->all());
        return $this->sendResponse($registeredUser['error'], $registeredUser['code'], $registeredUser['message'], $registeredUser['data']);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/forgot",
     *     summary="Forgot Password",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"email"},
     *                 @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Forgot Password Email Sent Successfully"),
     *     @OA\Response(response=401, description="Data Incomplete or Invalid"),
     *     @OA\Response(response=422, description="Validation Error"),
     *     @OA\Response(response=500, description="Internal Server Error"),
     * 
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function forgot(ForgotRequest $request, AuthInterface $auth)
    {
        $authUser = $auth->forgot($request->get('email'));
        return $this->sendResponse($authUser['error'], $authUser['code'], $authUser['message'], $authUser['data']);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/reset",
     *     summary="Reset Password",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"otp", "password", "password_confirmation"},
     *                 @OA\Property(property="otp", type="string", format="string", example="123456"),
     *                 @OA\Property(property="password", type="string", format="string", example="password123"),
     *                 @OA\Property(property="password_confirmation", type="string", format="string", example="password123"),
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Forgot Password Email Sent Successfully"),
     *     @OA\Response(response=401, description="Data Incomplete or Invalid"),
     *     @OA\Response(response=422, description="Validation Error"),
     *     @OA\Response(response=500, description="Internal Server Error"),
     * 
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function reset(ResetRequest $request, AuthInterface $auth)
    {
        $authUser = $auth->reset($request->only('otp', 'password'), );
        return $this->sendResponse($authUser['error'], $authUser['code'], $authUser['message'], $authUser['data']);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/upload",
     *     summary="Upload a file",
     *     description="Upload a file to the server",
     *     tags={"Upload"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"file"},
     *                 @OA\Property(
     *                     property="file",
     *                     type="string",
     *                     format="binary",
     *                     description="The file to be uploaded"
     *                 ),
     *                 @OA\Property(
     *                     property="path",
     *                     type="string",
     *                     description="The path where the file will be uploaded"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="File uploaded successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="boolean",
     *                 description="Error status"
     *             ),
     *             @OA\Property(
     *                 property="code",
     *                 type="integer",
     *                 description="Response code"
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 description="Response message"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 description="Response data"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="boolean",
     *                 description="Error status"
     *             ),
     *             @OA\Property(
     *                 property="code",
     *                 type="integer",
     *                 description="Response code"
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 description="Response message"
     *             )
     *         )
     *     )
     * )
     */
    public function upload(UploadRequest $request, AuthInterface $auth)
    {
        $authUser = $auth->upload($request->get('path'), $request->file('file'));
        return $this->sendResponse($authUser['error'], $authUser['code'], $authUser['message'], $authUser['data']);
    }
}