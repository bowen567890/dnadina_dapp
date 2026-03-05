<?php

namespace App\Http\Controllers\Api\V1\Common;
/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 *  新闻/资讯服务
 *
 * @author   m.y
 * @package App\Http\Controllers\Api\V1\News
 * @date   2020/10/30 9:13
 * @explain
 *
 */

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\New\NewResource;
use App\Http\Resources\New\NewsResource;
use App\Models\Common\News;
use Illuminate\Http\Request;

class NewsController extends ApiController
{


    protected $news;

    public function __loadBusinessService()
    {
    	$this->news  = News::query();
    }


    /**
     *  列表.fix
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\BusinessException;
     *
     */
    public function list(Request $request)
    {
      try {
            //loading business
            $this->__loadBusinessService();
            $limit = (int)$request->input('page_size', 10);
            return $this->response(NewsResource::collection($this->news->where('status',1)->orderByDesc('is_top')->orderByDesc('pushd_at')->paginate($limit)));

      } catch (\Exception $exception) {
       return $this->__responseError($exception->getMessage(),$exception->getCode());

      }
    }


    /**
     *  新闻.fix
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\BusinessException;
     *
     */
    public function get(Request $request)
    {
      try {

             //loading business
            $this->__loadBusinessService();
            $id = (int)$request->input('id');
            $new = $this->news->where('id',$id)->first();
         //   $this->news->where('id',$id)->increment('fake_read_nums',10)->increment('read_nums',1);
            return $this->response(NewResource::make($new));

      } catch (\Exception $exception) {
       return $this->__responseError($exception->getMessage(),$exception->getCode());

      }
    }


}
