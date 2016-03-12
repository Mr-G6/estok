<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\WareHouse;

class DashboardController extends Controller{
    /**
     * Renders Dashboard View
     * @return $this
     */
    public function index(){

        $warehouses = WareHouse::all();
        for($i = 0; $i < count($warehouses); $i++ ){
            $warehouses[$i]->products_count = Products::where('wh_id','=',$warehouses[$i]->id)->count();
            $product = Products::where('wh_id','=',$warehouses[$i]->id)->orderBy('created_at','DESC')->get();
            if($product->count()){
                $product = $product->first();
                $warehouses[$i]->updated =  date_format($product->created_at, 'Y-m-d');
            }

        }
        return view('dashboard')->with('warehouses', $warehouses);
    }
}