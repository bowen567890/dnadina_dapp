<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class UserDhtLock extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'user_dht_lock';
    
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
