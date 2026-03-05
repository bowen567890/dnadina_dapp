<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class FundPool extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'fund_pool';
    
}
