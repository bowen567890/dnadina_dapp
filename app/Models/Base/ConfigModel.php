<?php

namespace App\Models\Base;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class ConfigModel extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'config';
    
}
