<?php

namespace Dcat\Admin\Form\Extend\Sku\Controllers;

use Dcat\Admin\Http\Controllers\AdminController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class UploadController extends AdminController
{
    /**
     * 上传图片.
     *
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function store(): JsonResponse
    {
        if (request()->hasFile('file')) {
            $file = request()->file('file');

            // 验证文件类型
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
                return response()->json(['error' => '仅支持上传图片文件（jpg, png, gif, webp）'], 400);
            }

            $disk = config('admin.upload.disk');
            $path = Storage::disk($disk)->put('sku', $file);
            $response = ['full_url' => Storage::disk($disk)->url($path), 'short_url' => $path];

            return response()->json($response);
        }
    }

    /**
     * 删除图片.
     *
     * @return JsonResponse
     */
    public function delete(): JsonResponse
    {
        $disk = config('admin.upload.disk');
        $path = request()->input('path');
        if (!Storage::disk($disk)->exists($path)) {
            return response()->json(['code' => 404, 'message' => '未找到相关图片']);
        }

        try {
            Storage::disk($disk)->delete($path);

            return response()->json(['code' => 200, 'message' => '删除成功']);
        } catch (\Exception $exception) {
            return response()->json(['code' => $exception->getCode(), 'message' => $exception->getMessage()]);
        }
    }
}
