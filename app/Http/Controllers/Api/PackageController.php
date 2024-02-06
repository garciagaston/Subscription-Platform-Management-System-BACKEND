<?php

namespace App\Http\Controllers\Api;

use App\Models\Package;
use App\Http\Controllers\Controller;
use App\Http\Requests\Package\PackageIndexRequest;
use App\Http\Requests\Package\PackageRequest;
use App\Http\Resources\PackageResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class PackageController extends Controller
{
    /**
     * Get Packages
     *
     * @OA\Get(
     *     path="/api/v1/packages",
     *     tags={"Packages"},
     *     summary="Get packages",
     *     operationId="PackagesIndex",
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
     *         name="sku",
     *         in="query",
     *         description="sku",
     *         example="SKU1234",
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
     *                  @OA\Items( ref="#/components/schemas/PackageResource")
     *              )
     *         )
     *     )
     * )
     *
     * @param  PackageIndexRequest $request
     * @return AnonymousResourceCollection | JsonResponse
     */
    public function index(PackageIndexRequest $request): AnonymousResourceCollection | JsonResponse
    {
        $this->authorize('viewAny', auth()->user());
        $paginatedData = Package::filter($request)->paginate($request->per_page ?? config('pagination.default_items'));
        return PackageResource::collection($paginatedData);
    }

    /**
     * Get package by ID
     *
     * @OA\Get(
     *     path="/api/v1/packages/{id}",
     *     tags={"Packages"},
     *     summary="Get Package",
     *     operationId="GetPackage",
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
     *         description="Package ID",
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
     *                  ref="#/components/schemas/PackageResource"
     *              )
     *         )
     *     )
     * )
     *
     * @param Package $package
     * @return JsonResponse
     */
    public function show(Package $package): JsonResponse
    {
        $this->authorize('view', $package);
        return response()->json([
            'status' => 'success',
            'data' => new PackageResource($package)
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/packages/",
     *     tags={"Packages"},
     *     summary="Create Package",
     *     operationId="PackageStore",
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
     *                 ref="#/components/schemas/PackageRequest",
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
     *                  ref="#/components/schemas/PackageResource"
     *              )
     *         )
     *     ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=422, description="Unprocessable Entity")
     * )
     *
     * @param PackageRequest $request
     * @return JsonResponse
     */
    public function store(PackageRequest $request): JsonResponse
    {
        $this->authorize('create', auth()->user());
        $package = Package::create($request->validated());
        return response()->json([
            'status' => 'success',
            'data' => new PackageResource($package)
        ], Response::HTTP_CREATED);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/packages/{id}",
     *     tags={"Packages"},
     *     summary="Edit Package",
     *     operationId="PackageUpdate",
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
     *         description="Package ID",
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
     *                 ref="#/components/schemas/PackageRequest",
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
     *                  ref="#/components/schemas/PackageResource"
     *              )
     *         )
     *     ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=422, description="Unprocessable Entity")
     * )
     *
     * @param PackageRequest $request
     * @param Package $package
     * @return JsonResponse
     */
    public function update(PackageRequest $request, Package $package): JsonResponse
    {
        $this->authorize('update', $package);
        $package->update($request->validated());
        return response()->json([
            'status' => 'success',
            'data' => new PackageResource($package)
        ], Response::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/packages/{id}",
     *     tags={"Packages"},
     *     summary="Delete Package",
     *     operationId="PackageDelete",
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
     *         description="Package ID",
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
     * @param Package $package
     * @return JsonResponse
     */
    public function destroy(Package $package): JsonResponse
    {
        $this->authorize('delete', $package);
        $package->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Package deleted successfully.'
        ], Response::HTTP_OK);
    }
}
