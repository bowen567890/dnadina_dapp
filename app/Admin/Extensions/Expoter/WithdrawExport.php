<?php

namespace App\Admin\Extensions\Expoter;

use Aoding9\Dcat\Xlswriter\Export\BaseExport;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class WithdrawExport extends BaseExport
{

    public $header = [
        ['column' => 'a', 'width' => 25, 'name' => '用户ID'],
        ['column' => 'b', 'width' => 25, 'name' => '用户地址'],
        ['column' => 'c', 'width' => 25, 'name' => '订单号'],
        ['column' => 'd', 'width' => 10, 'name' => '提现数量'],
        ['column' => 'd', 'width' => 10, 'name' => '提现币种'],
        ['column' => 'e', 'width' => 30, 'name' => '手续费率'],
        ['column' => 'f', 'width' => 20, 'name' => '手续费'],
        ['column' => 'h', 'width' => 80, 'name' => '到账金额'],
        ['column' => 'm', 'width' => 20, 'name' => '状态'],
        ['column' => 'm', 'width' => 20, 'name' => '到账时间'],
        ['column' => 'p', 'width' => 20, 'name' => '创建时间'],
    ];

    public $fileName = '提现记录导出表'; // 导出的文件名
    public $tableTitle = '提现记录导出表'; // 第一行标题
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

        $statusArr = [1=>'待审核',2=>'审核通过',3=>'审核未通过'];

        foreach ($this->chunkData as $rowData) {

            //由于产品一对多，导出需要叠加多行
            $rowData = $rowData->toArray();
            $rowData['user_id'] = $rowData['user']['id'];
            $rowData['address'] = $rowData['user']['address'];
            $rowData['fee'] = $rowData['fee'].'%';
            $rowData['ac_amount'] = '到账金额: '.$rowData['ac_amount'];
            $rowData['status'] = $statusArr[$rowData['status']];
            $rowData['coin'] = $rowData['coin_id'] == 1 ? 'USDT' : 'FAC';

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
            $row['no'],
            $row['num'],
            $row['coin'],
            $row['fee'],
            $row['fee_amount'],
            $row['ac_amount'],
            $row['status'],
            $row['finsh_time'],
            $row['created_at'],
        ];
    }


}
