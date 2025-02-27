<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserIndexRequest;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class UsersController extends Controller
{
    /**
     * Get Users
     *
     * @OA\Get(
     *     path="/api/v1/users",
     *     tags={"Users"},
     *     summary="Get users",
     *     operationId="UsersIndex",
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
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Items per page.",
     *         example="5",
     *         required=false,
     *
     *         @OA\Schema(
     *             type="numeric"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page",
     *         example="1",
     *         required=false,
     *
     *         @OA\Schema(
     *             type="numeric"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="name",
     *         example="jon",
     *         required=false,
     *
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="email",
     *         example="jon.snow@example.com",
     *         required=false,
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
     *                  property="data",
     *                  type="array",
     *
     *                  @OA\Items( ref="#/components/schemas/UserResource")
     *              )
     *         )
     *     )
     * )
     */
    public function index(UserIndexRequest $request): AnonymousResourceCollection|JsonResponse
    {
        try {
            $this->authorize('viewAny', auth()->user());
            $paginatedData = User::filter($request)->paginate($request->per_page ?? config('pagination.default_items'));

            return UserResource::collection($paginatedData);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        } catch (Exception $e) {
            logger()->error($e->getMessage());

            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Get user by ID
     *
     * @OA\Get(
     *     path="/api/v1/users/{id}",
     *     tags={"Users"},
     *     summary="Get User",
     *     operationId="GetUser",
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
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
     *         example="1",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
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
     *                  type="bool",
     *                  default=true,
     *                  property="success"
     *              ),
     *              @OA\Property(
     *                  property="user",
     *                  ref="#/components/schemas/UserResource"
     *              )
     *         )
     *     )
     * )
     */
    public function show(User $user): JsonResponse
    {
        try {
            $this->authorize('view', $user);

            return response()->json([
                'status' => 'success',
                'data' => new UserResource($user),
            ], Response::HTTP_OK);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        } catch (Exception $e) {
            logger()->error($e->getMessage());

            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/users/",
     *     tags={"Users"},
     *     summary="Create User",
     *     operationId="UserStore",
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
     *     @OA\RequestBody(
     *
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *
     *             @OA\Schema(
     *                 type="object",
     *                 ref="#/components/schemas/UserStoreRequest",
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *
     *          @OA\JsonContent(
     *              type="object",
     *
     *              @OA\Property(
     *                  type="bool",
     *                  default=true,
     *                  property="success"
     *              ),
     *              @OA\Property(
     *                  property="user",
     *                  ref="#/components/schemas/UserResource"
     *              )
     *         )
     *     ),
     *
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=422, description="Unprocessable Entity")
     * )
     */
    public function store(UserStoreRequest $request): JsonResponse
    {
        try {
            $this->authorize('create', auth()->user());
            $user = User::create($request->validated());
            $user->assignRole('user');

            return response()->json([
                'status' => 'success',
                'data' => new UserResource($user),
            ], Response::HTTP_CREATED);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        } catch (Exception $e) {
            logger()->error($e->getMessage());

            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/users/{id}",
     *     tags={"Users"},
     *     summary="Edit User",
     *     operationId="UserUpdate",
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
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
     *         example="1",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *
     *     @OA\RequestBody(
     *
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *
     *             @OA\Schema(
     *                 type="object",
     *                 ref="#/components/schemas/UserUpdateRequest",
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
     *                  type="bool",
     *                  default=true,
     *                  property="success"
     *              ),
     *              @OA\Property(
     *                  property="user",
     *                  ref="#/components/schemas/UserResource"
     *              )
     *         )
     *     ),
     *
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=422, description="Unprocessable Entity")
     * )
     */
    public function update(UserUpdateRequest $request, User $user): JsonResponse
    {
        try {
            $this->authorize('update', $user);
            $user->update($request->validated());

            return response()->json([
                'status' => 'success',
                'data' => new UserResource($user),
            ], Response::HTTP_OK);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        } catch (Exception $e) {
            logger()->error($e->getMessage());

            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/users/{id}",
     *     tags={"Users"},
     *     summary="Delete User",
     *     operationId="UserDelete",
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
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
     *         example="1",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="numeric"
     *         )
     *     ),
     *
     *     @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *     ),
     * )
     */
    public function destroy(User $user): JsonResponse
    {
        try {
            $this->authorize('delete', $user);
            $user->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'User deleted successfully.',
            ], Response::HTTP_OK);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        } catch (Exception $e) {
            logger()->error($e->getMessage());

            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
