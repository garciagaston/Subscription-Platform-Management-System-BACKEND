<?php

namespace App\Http\Controllers\Api;

use App\Models\Channel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Channel\ChannelIndexRequest;
use App\Http\Requests\Channel\ChannelRequest;
use App\Http\Resources\ChannelResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ChannelController extends Controller
{
    /**
     * Get Channels
     *
     * @OA\Get(
     *     path="/api/v1/channels",
     *     tags={"Channels"},
     *     summary="Get channels",
     *     operationId="ChannelsIndex",
     *     security={ {"bearer_token": {} }},
     *     @OA\Parameter(
     *         name="Authorization",
     *         in="header",
     *         description="Oauth2 token",
     *         example="Bearer 1|iQ04fCi7gNIVCWnhgxubNJrdrIINlgnUkixEPfaA",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
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
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="name",
     *         example="HBO",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="call_sign",
     *         in="query",
     *         description="call_sign",
     *         example="HBO",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="active",
     *         in="query",
     *         description="active",
     *         example="1",
     *         required=false,
     *         @OA\Schema(
     *             type="bool"
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
     *                  @OA\Items( ref="#/components/schemas/ChannelResource")
     *              )
     *         )
     *     )
     * )
     *
     * @param  ChannelIndexRequest $request
     * @return AnonymousResourceCollection | JsonResponse
     */
    public function index(ChannelIndexRequest $request): AnonymousResourceCollection | JsonResponse
    {
        $this->authorize('viewAny', auth()->user());
        $paginatedData = Channel::filter($request)->paginate($request->per_page ?? config('pagination.default_items'));
        return ChannelResource::collection($paginatedData);
    }

    /**
     * Get channel by ID
     *
     * @OA\Get(
     *     path="/api/v1/channels/{id}",
     *     tags={"Channels"},
     *     summary="Get Channel",
     *     operationId="GetChannel",
     *     security={ {"bearer_token": {} }},
     *     @OA\Parameter(
     *         name="Authorization",
     *         in="header",
     *         description="Oauth2 token",
     *         example="Bearer 1|iQ04fCi7gNIVCWnhgxubNJrdrIINlgnUkixEPfaA",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Channel ID",
     *         example="1",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  type="bool",
     *                  default=true,
     *                  property="success"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/ChannelResource"
     *              )
     *         )
     *     )
     * )
     *
     * @param Channel $channel
     * @return JsonResponse
     */
    public function show(Channel $channel): JsonResponse
    {
        $this->authorize('view', $channel);
        return response()->json([
            'status' => 'success',
            'data' => new ChannelResource($channel)
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/channels/",
     *     tags={"Channels"},
     *     summary="Create Channel",
     *     operationId="ChannelStore",
     *     security={ {"bearer_token": {} }},
     *     @OA\Parameter(
     *         name="Authorization",
     *         in="header",
     *         description="Oauth2 token",
     *         example="Bearer 1|iQ04fCi7gNIVCWnhgxubNJrdrIINlgnUkixEPfaA",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 ref="#/components/schemas/ChannelRequest",
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  type="bool",
     *                  default=true,
     *                  property="success"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/ChannelResource"
     *              )
     *         )
     *     ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=422, description="Unprocessable Entity")
     * )
     *
     * @param ChannelRequest $request
     * @return JsonResponse
     */
    public function store(ChannelRequest $request): JsonResponse
    {
        $this->authorize('create', Channel::class);
        $channel = Channel::create($request->validated());
        return response()->json([
            'status' => 'success',
            'data' => new ChannelResource($channel)
        ], Response::HTTP_CREATED);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/channels/{id}",
     *     tags={"Channels"},
     *     summary="Edit Channel",
     *     operationId="ChannelUpdate",
     *     security={ {"bearer_token": {} }},
     *     @OA\Parameter(
     *         name="Authorization",
     *         in="header",
     *         description="Oauth2 token",
     *         example="Bearer 1|iQ04fCi7gNIVCWnhgxubNJrdrIINlgnUkixEPfaA",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Channel ID",
     *         example="1",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 ref="#/components/schemas/ChannelRequest",
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  type="bool",
     *                  default=true,
     *                  property="success"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/ChannelResource"
     *              )
     *         )
     *     ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=422, description="Unprocessable Entity")
     * )
     *
     * @param ChannelRequest $request
     * @param Channel $channel
     * @return JsonResponse
     */
    public function update(ChannelRequest $request, Channel $channel): JsonResponse
    {
        $this->authorize('update', $channel);
        $channel->update($request->validated());
        return response()->json([
            'status' => 'success',
            'data' => new ChannelResource($channel)
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/channels/{id}",
     *     tags={"Channels"},
     *     summary="Delete Channel",
     *     operationId="ChannelDelete",
     *     security={ {"bearer_token": {} }},
     *     @OA\Parameter(
     *         name="Authorization",
     *         in="header",
     *         description="Oauth2 token",
     *         example="Bearer 1|iQ04fCi7gNIVCWnhgxubNJrdrIINlgnUkixEPfaA",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Channel ID",
     *         example="1",
     *         required=true,
     *         @OA\Schema(
     *             type="numeric"
     *         )
     *     ),
     *     @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *     ),
     * )
     *
     * @param Channel $channel
     * @return JsonResponse
     */
    public function destroy(Channel $channel): JsonResponse
    {
        $this->authorize('delete', $channel);
        $channel->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Channel deleted successfully.'
        ], Response::HTTP_OK);
    }
}
