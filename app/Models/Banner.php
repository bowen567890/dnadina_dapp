<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
	use HasDateTimeFormatter,Cachable;
    protected $table = 'banner';

}
