<?php

namespace App\Http\Controllers\Api;

use App\Models\Channel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Channel\ChannelIndexRequest;
use App\Http\Requests\Channel\ChannelRequest;
use App\Http\Resources\ChannelResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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

    public function store(ChannelRequest $request)
    {
        //
    }

    public function show(Channel $channel)
    {
        //
    }

    public function update(ChannelRequest $request, Channel $channel)
    {
        //
    }

    public function destroy(Channel $channel)
    {
        //
    }
}
