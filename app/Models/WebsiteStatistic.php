<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class WebsiteStatistic extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'website_statistics';
    public $timestamps = false;

}
