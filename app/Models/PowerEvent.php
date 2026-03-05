<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class PowerEvent extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'power_event';
    
}
