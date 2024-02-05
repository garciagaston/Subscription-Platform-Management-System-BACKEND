<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserIndexRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Items per page.",
     *         example="5",
     *         required=false,
     *         @OA\Schema(
     *             type="numeric"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page",
     *         example="1",
     *         required=false,
     *         @OA\Schema(
     *             type="numeric"
     *         )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items( ref="#/components/schemas/UserResource")
     *              )
     *         )
     *     )
     * )
     *
     * @param  Request $request
     * @return AnonymousResourceCollection | JsonResponse
     */
    public function index(UserIndexRequest $request): AnonymousResourceCollection | JsonResponse
    {
        $this->authorize('index', auth()->user());
        $paginatedData = User::filter($request)->paginate($request->per_page ?? config('pagination.default_items'));
        return UserResource::collection($paginatedData);
    }

    public function show(User $user): JsonResponse
    {
        $this->authorize('show', $user);
        return response()->json([
            'status' => 'success',
            'data' => new UserResource($user)
        ], Response::HTTP_OK);
    }

    public function store(UserStoreRequest $request): JsonResponse
    {
        $this->authorize('store', auth()->user());
        $user = User::create($request->validated());
        $user->assignRole('user');
        return response()->json([
            'status' => 'success',
            'data' => new UserResource($user)
        ], Response::HTTP_CREATED);
    }


    public function update(Request $request, User $user): JsonResponse
    {
        $this->authorize('update', $user->id);
        $user->update($request->all());
        return response()->json([
            'status' => 'success',
            'data' => new UserResource($user)
        ], Response::HTTP_OK);
    }

    public function destroy(User $user): JsonResponse
    {
        $this->authorize('destroy', $user->id);
        $user->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully.'
        ], Response::HTTP_NO_CONTENT);
    }
}
