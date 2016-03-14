<?php namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\WareHouse;
use Illuminate\Http\Request;

class CheckoutController extends Controller{

    public function index($id){
        $warehouse = WareHouse::where('id','=',$id)->get();
        if(count($warehouse)){
            $warehouse = $warehouse->first();
            return view('checkout')->with('warehouse', $warehouse);
        }
        return redirect('/');
    }

    public function getProducts($id){
        $products = Products::where('wh_id','=',$id);
        return response()->json($products->lists('name'));
    }

    public function getProductDetails(Request $request){
        $wh_id = $request->input('wh_id');
        $name = $request->input("name");
        $product = Products::where('wh_id', '=', $wh_id)
                            ->where('name','=', $name)
                            ->get()
                            ->first();
        return response()->json($product);
    }

    public function validateQuantity(Request $request){
        $wh_id = $request->input('wh_id');
        $name = $request->input('name');

        $quantity = Products::where('wh_id', '=', $wh_id)
                        ->where('name','=', $name)
                        ->get()
                        ->first()->quantity;
        return response()->json($quantity);
    }

    public function checkout(Request $request){
        $wh_id = $request->input('wh_id');
        $items = json_decode($request->input('items'));
        foreach($items as $item){
            Products::where('wh_id','=',$wh_id)->where('name','=',$item->name)->decrement('quantity',$item->quantity);
        }
        return response()->json(true);
    }
}