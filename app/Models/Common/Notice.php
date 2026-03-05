<?php

namespace App\Models\Common;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
	use HasDateTimeFormatter,Cachable;



    protected $casts = [
        'status'  => 'bool',
        'title'   => 'json',
        'content' => 'json',
    ];
}
