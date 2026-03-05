<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class DhtLockOrder extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'dht_lock_order';
    
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
