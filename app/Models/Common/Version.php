<?php

namespace App\Models\Common;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    use HasDateTimeFormatter,Cachable;

    protected $table = 'version';

}
