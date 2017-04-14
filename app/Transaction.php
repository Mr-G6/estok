<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    public function product(){
        return $this->belongsTo('App\Products', 'product_id', 'id');
    }
}
