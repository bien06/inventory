<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemAssign extends Model
{
    protected $table = 'item_assignments';
    
    public function item(){
        return $this->belongsTo('App\Item', 'item_id', 'id');
    }
}
