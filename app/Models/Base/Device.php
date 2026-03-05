<?php

namespace App\Models\Base;

use App\Models\User;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use MongoDB\Laravel\Eloquent\Model;


class Device extends Model
{

    use HasDateTimeFormatter,Cachable;

    protected $guarded = [];

    protected $casts = [

    ];

    public function user(){
        return $this->belongsTo(User::class);
    }


    public function ips()
    {
        return $this->hasMany(Device::class, 'ip', 'ip');
    }


}
