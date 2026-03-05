<?php

namespace App\Admin\Extensions\Expoter;

use Aoding9\Dcat\Xlswriter\Export\BaseExport;
use App\Enums\SettleType;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class RechargeExport extends BaseExport
{

    public $header = [
        ['column' => 'a', 'width' => 25, 'name' => '用户ID'],
        ['column' => 'b', 'width' => 25, 'name' => '用户地址'],
        ['column' => 'c', 'width' => 30, 'name' => '订单号'],
        ['column' => 'd', 'width' => 20, 'name' => '充值数量'],
        ['column' => 'e', 'width' => 20, 'name' => '充值类型'],
        ['column' => 'f', 'width' => 80, 'name' => '充值Hash'],
        ['column' => 'g', 'width' => 20, 'name' => '充值时间'],
    ];

    public $fileName = '充值记录导出表'; // 导出的文件名
    public $tableTitle = '充值记录导出表'; // 第一行标题
    private $lastItem = [];
    private $mergeNum = 1;


    public function insertChunkData(Collection $data) {
        $this->chunkData = $data;
        $index = $this->getIndex();

        // 给每行数据绑定序号
        foreach ($this->chunkData as $k => $rowData) {
            if ($rowData instanceof Model) {
                $rowData->index = $index;
            } else {
                $rowData['index'] = $index;
                $this->chunkData->put($k, $rowData);
            }
            $index++;
        }

        $typeArr = [1=>'购买节点'];

        foreach ($this->chunkData as $rowData) {

            //由于产品一对多，导出需要叠加多行
            $rowData = $rowData->toArray();
            $rowData['user_id'] = $rowData['user']['id'];
            $rowData['address'] = $rowData['user']['address'];
            $rowData['nums'] = $rowData['nums'].'USDT';
            $rowData['type_text'] = $typeArr[$rowData['type']];

            $this->setRowHeight();
            // 将数据传给eachRow，实现与列的对应关联
            $rowArray = $this->eachRow($rowData);
            // 循环该行的每个数据，插入单元格
            foreach ($rowArray as $column => $columnData) {
                $this->insertCell($this->currentLine, $column, $columnData);
            }
            $this->index++;
            $this->currentLine++;
            $this->lastItem = $rowData;
        }

        unset($rowArray, $column);

        return $this;
    }


    // public $debug=true;
    // 将模型字段与表头关联
    public function eachRow($row) {
        return [
            $row['user_id'],
            $row['address'],
            $row['order_no'],
            $row['nums'],
            $row['type_text'],
            $row['hash'],
            $row['created_at'],
        ];
    }


}
