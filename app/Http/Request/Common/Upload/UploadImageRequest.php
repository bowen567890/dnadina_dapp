<?php

namespace App\Http\Request\Common\Upload;

use App\Http\Request\BaseRequest;

class UploadImageRequest extends BaseRequest
{

    public function rules(): array
    {
        return [
            'file' => 'required|image|mimes:jpeg,png,jpg,bmp,gif|max:20000',
            'upload_type' => 'required|in:avatar,identity,feedback'
        ];
    }


    public function messages(): array
    {
        return [
            'file.required' => '文件不能为空',
            'file.image' => '图片类型,只支持(png,jpg,jpeg)',
            'file.max' => '文件不能大20M',
            'upload_type.required' => '类型不能为空',
            'upload_type.in' => '类型错误'
        ];
    }



}
