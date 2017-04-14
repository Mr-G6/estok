<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $table = 'products';

    public function totalCost(){
        return $this->unit_price * $this->quantity;
    }
}
