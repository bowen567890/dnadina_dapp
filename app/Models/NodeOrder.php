<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class NodeOrder extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'node_order';
    
    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
