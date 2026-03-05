<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class FundPoolLog extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'fund_pool_log';
    public $timestamps = false;

}
