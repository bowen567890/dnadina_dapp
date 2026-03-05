<?php

namespace App\Http\Controllers\Api\V1\Common;

use App\Http\Controllers\Api\ApiController;
use App\Http\Request\Common\Upload\UploadImageRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class UploadController extends ApiController
{

    /**
     * 上传图片
     * @param UploadImageRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function uploadImage(UploadImageRequest $request): JsonResponse
    {#
        try {
            // 使用minio磁盘
            $disk = Storage::disk('minio');
            $data = $request->only(['file','upload_type']);
            $file = $request->file('file');
            $imageSize = getimagesize($file);
            if ($imageSize === false) {
                throw new Exception(Lang('上传失败'), 400);
            }
            
            // 生成唯一文件名
            $filename = md5(time() . $file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();
            $uploadPath = $data['upload_type'] . '/' . $filename;
            
            // 上传文件并设置为公共可访问
            $path = $disk->putFileAs($data['upload_type'], $file, $filename, 'public');
            
            // 如果上传成功，尝试设置文件权限
            if ($path) {
                try {
                    $disk->setVisibility($path, 'public');
                } catch (Exception $e) {
                    // 权限设置失败但文件已上传，记录日志但不中断流程
                    \Log::warning('文件权限设置失败: ' . $e->getMessage());
                }
            }
            
            return $this->response([
                'path'  => $path,
                'type'  => $data['upload_type'],
                'url'   => ImageUrl($path, 'minio')
            ]);
        }catch (\Exception $exception){
            return $this->__responseError($exception->getMessage(),$exception->getCode());
        }
    }

}
