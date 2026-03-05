<?php

namespace App\Models\Common;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use MongoDB\Laravel\Eloquent\Model;

class MailModel extends Model
{

    use HasDateTimeFormatter;

    protected $table = "email_logs";

    protected $connection = "mongodb";

    protected $guarded = [];

}
