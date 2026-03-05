<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class NodePeriod extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'node_period';
    
    public function config()
    {
        return $this->hasOne(NodeConfig::class, 'lv', 'lv');
    }
}
