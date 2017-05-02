<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventory';

    public function products(){
        return $this->hasMany('App\Products', 'inventory_id', 'id');
    }

}
