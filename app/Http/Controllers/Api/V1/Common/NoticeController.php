<?php

namespace App\Http\Controllers\Api\V1\Common;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Notice\NoticeResource;
use App\Models\Common\Notice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NoticeController extends ApiController
{

    /**
     *  公告
     *
     * @return JsonResponse
     *
     */
    public function marquee(): JsonResponse
    {
        try {
            $notices = Notice::query()
                ->where('status', 1)
                ->orderBy('order', 'asc')
                ->orderBy('id', 'desc')
                ->get();
            return $this->response(NoticeResource::collection($notices));
        }catch (\Exception $exception){
            return $this->__responseError($exception->getMessage(),$exception->getCode());
        }
    }

    /**
     * 商品详情
     */
    public function detail(Request $request)
    {
        $in = $request->input();
        
        if (!isset($in['id']) || intval($in['id'])<=0) {
            throw new \Exception(Lang('请选择公告'));
        }
        $id = intval($in['id']);
        
        $where['id'] = $id;
        $where['status'] = 1;
        
        $notice = Notice::query()
            ->where($where)
            ->first(['id','title','content','updated_at']);
        if ($notice) {
            $notice = $notice->toArray();
            $notice['title'] = LocalDataGet($notice['title']);
            $notice['content'] = LocalDataGet($notice['content']);
        }
        return $this->response($notice);
    }

}
