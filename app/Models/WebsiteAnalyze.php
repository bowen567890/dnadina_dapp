<?php

namespace App\Models;

use App\Enums\WebsiteAnalyzeEnum;
use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class WebsiteAnalyze extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'website_analyze';
    public $timestamps = false;

    protected $fillable = [
        'date',
    ];


    public static function addData($field ,$num): void
    {
        $model = self::query()->firstOrCreate(['date'=>date('Y-m-d')]);
        self::query()->where('id',$model->id)->increment($field,$num);
    }
}
