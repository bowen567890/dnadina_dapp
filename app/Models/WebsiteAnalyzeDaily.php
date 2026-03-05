<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class WebsiteAnalyzeDaily extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'website_analyze_daily';
    public $timestamps = false;

}
