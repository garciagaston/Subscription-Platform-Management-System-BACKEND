<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * New user registration
     *
     * @OA\Post(
     *     path="/api/v1/auth/register",
     *     tags={"Auth"},
     *     summary="New user registration",
     *     operationId="AuthRegister",
     *
     *     @OA\RequestBody(
     *
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *
     *             @OA\Schema(
     *                 type="object",
     *                 ref="#/components/schemas/RegisterRequest",
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *
     *          @OA\JsonContent(
     *              type="object",
     *
     *              @OA\Property(
     *                  type="string",
     *                  default="success",
     *                  property="status"
     *              ),
     *              @OA\Property(
     *                  type="string",
     *                  default="User is created successfully.",
     *                  property="message"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *
     *                  @OA\Items(
     *                      type="object",
     *
     *                      @OA\Property(property="token_type", type="string", example="bearer"),
     *                      @OA\Property(property="token", type="string", example="XXXXXXXXXXXXXXX"),
     *                      @OA\Property(property="user", type="array", @OA\Items( ref="#/components/schemas/UserResource")
     *                  )
     *              )
     *         )
     *     )),
     * )
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->assignRole('user');

        $data['token_type'] = 'bearer';
        $data['token'] = $user->createToken($request->email)->plainTextToken;
        $data['user'] = new UserResource($user);

        $response = [
            'status' => 'success',
            'message' => 'User is created successfully.',
            'data' => $data,
        ];

        return response()->json($response, Response::HTTP_CREATED);
    }

    /**
     * User login
     *
     * @OA\Post(
     *     path="/api/v1/auth/login",
     *     tags={"Auth"},
     *     summary="User login",
     *     operationId="AuthLogin",
     *
     *     @OA\RequestBody(
     *
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *
     *             @OA\Schema(
     *                 type="object",
     *                 ref="#/components/schemas/LoginRequest",
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *
     *          @OA\JsonContent(
     *              type="object",
     *
     *              @OA\Property(
     *                  type="string",
     *                  default="success",
     *                  property="status"
     *              ),
     *              @OA\Property(
     *                  type="string",
     *                  default="User is logged in successfully.",
     *                  property="message"
     *              ),
     *              @OA\Property(
     *              property="data",
     *              type="array",
     *
     *                  @OA\Items(
     *                      type="object",
     *
     *                      @OA\Property(property="token_type", type="string", example="bearer"),
     *                      @OA\Property(property="token", type="string", example="XXXXXXXXXXXXXXX"),
     *                      @OA\Property(property="user", type="array", @OA\Items( ref="#/components/schemas/UserResource")
     *              )
     *                  )
     *              )
     *         )
     *     ),
     * )
     */
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        // Check password
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Invalid credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $data['token_type'] = 'bearer';
        $data['token'] = $user->createToken($request->email)->plainTextToken;
        $data['user'] = new UserResource($user);

        $response = [
            'status' => 'success',
            'message' => 'User is logged in successfully.',
            'data' => $data,
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * User logout
     *
     * @OA\Post(
     *     path="/api/v1/auth/logout",
     *     tags={"Auth"},
     *     summary="User logout",
     *     operationId="AuthLogout",
     *     security={ {"bearer_token": {} }},
     *
     *     @OA\Parameter(
     *         name="Authorization",
     *         in="header",
     *         description="Oauth2 token",
     *         example="Bearer 1|iQ04fCi7gNIVCWnhgxubNJrdrIINlgnUkixEPfaA",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *
     *          @OA\JsonContent(
     *              type="object",
     *
     *              @OA\Property(
     *                  type="string",
     *                  default="success",
     *                  property="status"
     *              ),
     *              @OA\Property(
     *                  type="string",
     *                  default="User is logged out successfully.",
     *                  property="message"
     *              ),
     *         )
     *     ),
     * )
     */
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User is logged out successfully',
        ], Response::HTTP_OK);
    }
}
