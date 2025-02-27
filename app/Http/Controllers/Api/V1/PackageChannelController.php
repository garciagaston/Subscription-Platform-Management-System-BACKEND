<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\PackageHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\PackageResource;
use App\Models\Channel;
use App\Models\Package;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class PackageChannelController extends Controller
{
    /**
     * Attach a channel to a package
     *
     * @OA\Post(
     *     path="/api/v1/packages/{packageId}/channels/{channelId}",
     *     tags={"Packages"},
     *     summary="Attach a channel to a package",
     *     operationId="PackageChannelAttach",
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
     *         name="packageId",
     *         in="path",
     *         description="Package ID",
     *         example="1",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="channelId",
     *         in="path",
     *         description="Channel ID",
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
     *                  ref="#/components/schemas/PackageResource"
     *              )
     *         )
     *     ),
     *
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=422, description="Unprocessable Entity")
     * )
     */
    public function attach(Package $package, Channel $channel): JsonResponse
    {
        try {
            DB::beginTransaction();
            $this->authorize('attach', Package::class);
            PackageHelper::attachChannelToPackage($package, $channel);
            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => new PackageResource($package),
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollback();
            logger()->error($e->getMessage());

            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Detach a channel from a package
     *
     * @OA\Delete(
     *     path="/api/v1/packages/{packageId}/channels/{channelId}",
     *     tags={"Packages"},
     *     summary="Detach a channel from a package",
     *     operationId="PackageChannelDetach",
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
     *         name="packageId",
     *         in="path",
     *         description="Package ID",
     *         example="1",
     *         required=true,
     *
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="channelId",
     *         in="path",
     *         description="Channel ID",
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
     *                  ref="#/components/schemas/PackageResource"
     *              )
     *         )
     *     ),
     *
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=422, description="Unprocessable Entity")
     * )
     */
    public function detach(Package $package, Channel $channel): JsonResponse
    {
        try {
            DB::beginTransaction();
            $this->authorize('detach', Package::class);
            PackageHelper::detachChannelToPackage($package, $channel);
            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => new PackageResource($package),
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            DB::rollback();
            logger()->error($e->getMessage());

            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
