<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subscription\SubscriptionIndexRequest;
use App\Http\Requests\Subscription\SubscriptionRequest;
use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class SubscriptionController extends Controller
{
    /**
     * Get Subscriptions
     *
     * @OA\Get(
     *     path="/api/v1/subscriptions",
     *     tags={"Subscriptions"},
     *     summary="Get subscriptions",
     *     operationId="SubscriptionsIndex",
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
     *         name="user_id",
     *         in="query",
     *         description="user_id",
     *         example="1",
     *         required=false,
     *
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="package_id",
     *         in="query",
     *         description="package_id",
     *         example="1",
     *         required=false,
     *
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="active",
     *         in="query",
     *         description="active",
     *         example="1",
     *         required=false,
     *
     *         @OA\Schema(
     *             type="bool"
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
     *                  @OA\Items( ref="#/components/schemas/SubscriptionResource")
     *              )
     *         )
     *     )
     * )
     */
    public function index(SubscriptionIndexRequest $request): AnonymousResourceCollection|JsonResponse
    {
        $this->authorize('viewAny', auth()->user());
        $paginatedData = Subscription::filter($request)->paginate($request->per_page ?? config('pagination.default_items'));

        return SubscriptionResource::collection($paginatedData);
    }

    /**
     * Get subscription by ID
     *
     * @OA\Get(
     *     path="/api/v1/subscriptions/{id}",
     *     tags={"Subscriptions"},
     *     summary="Get Subscription",
     *     operationId="GetSubscription",
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
     *         description="Subscription ID",
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
     *                  property="data",
     *                  ref="#/components/schemas/SubscriptionResource"
     *              )
     *         )
     *     )
     * )
     */
    public function show(Subscription $subscription): JsonResponse
    {
        $this->authorize('view', $subscription);

        return response()->json([
            'status' => 'success',
            'data' => new SubscriptionResource($subscription),
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/subscriptions/",
     *     tags={"Subscriptions"},
     *     summary="Create Subscription",
     *     operationId="SubscriptionStore",
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
     *                 ref="#/components/schemas/SubscriptionRequest",
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
     *                  property="data",
     *                  ref="#/components/schemas/SubscriptionResource"
     *              )
     *         )
     *     ),
     *
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=422, description="Unprocessable Entity")
     * )
     */
    public function store(SubscriptionRequest $request): JsonResponse
    {
        $this->authorize('create', Subscription::class);
        $subscription = Subscription::create($request->validated());

        return response()->json([
            'status' => 'success',
            'data' => new SubscriptionResource($subscription),
        ], Response::HTTP_CREATED);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/subscriptions/{id}",
     *     tags={"Subscriptions"},
     *     summary="Edit Subscription",
     *     operationId="SubscriptionUpdate",
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
     *         description="Subscription ID",
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
     *                 ref="#/components/schemas/SubscriptionRequest",
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
     *                  property="data",
     *                  ref="#/components/schemas/SubscriptionResource"
     *              )
     *         )
     *     ),
     *
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=422, description="Unprocessable Entity")
     * )
     */
    public function update(SubscriptionRequest $request, Subscription $subscription): JsonResponse
    {
        $this->authorize('update', $subscription);
        $subscription->update($request->validated());

        return response()->json([
            'status' => 'success',
            'data' => new SubscriptionResource($subscription),
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/subscriptions/{id}",
     *     tags={"Subscriptions"},
     *     summary="Delete Subscription",
     *     operationId="SubscriptionDelete",
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
     *         description="Subscription ID",
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
    public function destroy(Subscription $subscription): JsonResponse
    {
        $this->authorize('delete', $subscription);
        $subscription->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Subscription deleted successfully.',
        ], Response::HTTP_OK);
    }
}
