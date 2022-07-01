<?php

namespace App\Services\Controllers\FileManager;

use App\Core\Http\Controllers\Controller;
use App\Core\Traits\ApiResponder;
use Encore\Admin\Actions\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\Service\FiltersService;
use App\Services\Service\FileManagerService;
use Illuminate\Support\Facades\Auth;
use App\Core\Traits\UploadImage;
use App\Core\Helpers\FileManager\CheckFile;

class FileManagerController extends Controller
{
    use ApiResponder, UploadImage;

    public $fileManagerService;

    /**
     * Create a new controller instance.
     *
     * @param FiltersService $fileManagerService
     */

    public function __construct(FileManagerService $fileManagerService)
    {
        $this->fileManagerService = $fileManagerService;
        app()->setLocale(isset($request['data']['lang']) && $request['data']['lang'] ? $request['data']['lang'] : config('app.lang'));
    }

    /**
     * @OA\Get(
     *      path="/file_manager/file/{id}",
     *      tags={"File Manager"},
     *      summary="Get File by Id",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  title="Get Data File",
     *                  ref="#/components/schemas/FileManager"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function show($domain, $id): JsonResponse
    {
        return $this->successResponse($this->fileManagerService->getFile($id));
    }

    /**
     * @OA\Post(
     *      path="/file_manager/file",
     *      tags={"File Manager"},
     *      summary="Upload Image",
     *      @OA\RequestBody(
     *          description="Upload file",
     *          required=true,
     *          @OA\JsonContent(
     *                  @OA\Property(
     *                      property="data",
     *                          @OA\Property(
     *                              property="base64_file",
     *                              title="Format base64 file",
     *                              example="code",
     *                              type="string"
     *                          ),
     *                          @OA\Property(
     *                              property="type",
     *                              title="Type file",
     *                              example="png",
     *                              type="string"
     *                          ),
     *                          @OA\Property(
     *                              property="title",
     *                              title="Name file",
     *                              example="test",
     *                              type="string"
     *                          ),
     *                          @OA\Property(
     *                              property="is_image",
     *                              example=true,
     *                              type="boolean"
     *                          ),
     *                      )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  title="Get Data File",
     *                  ref="#/components/schemas/FileManager"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function store(Request $request): JsonResponse
    {
        $data = $request->data;

        if($data['is_image']){
            $validate = CheckFile::validateImage($data['base64_file'], $data['type']);
            if(isset($validate['message'])){
                return $this->errorResponse($validate['message'], $validate['code']);
            };

            if($upload = $this->uploadImageBase64(optional(Auth::user()->company)->uniq_id, $data['base64_file'], $data['type'],$data['title'] ?? null))
            {
                return $this->successResponse($this->fileManagerService->createFile($upload));
            }

            return $this->errorResponse('Error upload file', \Illuminate\Http\Response::HTTP_OK);
        }

        //TODO validate and download file
    }

    /**
     * @OA\Get(
     *      path="/file_manager/list",
     *      tags={"File Manager"},
     *      summary="Get List Files",
     *      @OA\Parameter(
     *         name="page",
     *         description="Page Number",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *              type="integer"
     *          )
     *       ),
     *       @OA\Parameter(
     *           name="s",
     *           description="Search",
     *           required=false,
     *           in="query",
     *           @OA\Schema(
     *                type="string"
     *           )
     *      ),
     *      @OA\Parameter(
     *           name="is_image",
     *           description="Filter by images",
     *           required=false,
     *           in="query",
     *           @OA\Schema(
     *                type="boolean"
     *           )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get List Files",
     *                 @OA\Property(
     *                    property="list",
     *                    title="Get List Data",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/FileManager"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="total",
     *                    title="Total Data",
     *                    type="integer",
     *                  ),
     *                  @OA\Property(
     *                    property="total_page",
     *                    title="Total Page",
     *                    type="integer",
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function list(Request $request): JsonResponse
    {
        return $this->successResponse($this->fileManagerService->getListFiles());
    }

    /**
     * @OA\Post(
     *      path="/file_manager/delete",
     *      tags={"File Manager"},
     *      summary="Remove Images",
     *      @OA\RequestBody(
     *          description="Remove Images",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    type="array",
     *                    collectionFormat="multi",
     *                    property="data",
     *                    @OA\Items(
     *                       @OA\Property(
     *                          property="id",
     *                          type="integer",
     *                          example=5,
     *                       ),
     *                  )
     *               )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function delete(): JsonResponse
    {
        $removedFiles = $this->successResponse($this->fileManagerService->delete());
        //Removed from aws
        if($files = $removedFiles->getData()->data){
            $this->removeFiles($files);
            return $this->successMessage('Successful operation', \Illuminate\Http\Response::HTTP_OK);
        }

        return $this->successMessage('Not Found', \Illuminate\Http\Response::HTTP_OK);
    }
}


