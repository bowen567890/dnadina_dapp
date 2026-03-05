<?php

namespace App\Services\Common;

use App\Models\Holiday;
use App\Services\BaseService;
use Carbon\Carbon;

class HolidayService extends BaseService
{

    /**
     * 批量更新节假日
     * @param array $data
     * @return void
     */
    public function updateHoliday(array $data = []): void
    {
        foreach ($data as $item){
            Holiday::query()->updateOrCreate([
                'date' => $item['date'],
            ],[
                'date' => $item['date'],
                'type' => $item['wage'],
                'holiday' => $item['holiday'] ? 1 : 0,
                'desc' => $item['name'],
            ]);
        }
    }

    /**
     * 检查是否是正常的工作日 true则为工作日 否则就不是工作日
     * @param $date
     * @return bool
     */
    public function check($date = null) : bool
    {
        if (empty($date)) {
            $date = date('Y-m-d');
        }
        $holiday = Holiday::query()->where('date',$date)->first();
        //数据库未找到配置
        if (empty($holiday)) {
            //以是否周末来判断是否工作日
            return (!Carbon::parse($date)->isSaturday() && !Carbon::parse($date)->isSunday());
        }else{
            return $holiday->holiday == 0;
        }
    }


}
