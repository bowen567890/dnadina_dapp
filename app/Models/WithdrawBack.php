<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class WithdrawBack extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'users_withdraw';

    protected $fillable = [
        'no',
        'coin_id',
        'user_id',
        'num',
        'fee',
        'fee_amount',
        'ac_amount',
        'status',
        'finsh_time',
        'is_push',
    ];


    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

}
