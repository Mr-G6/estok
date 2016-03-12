<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\WareHouse;
use Illuminate\Http\Request;

class WareHouseController extends Controller{

    /**
     * Render Warehouse Products View
     * @param $id
     * @return mixed
     */
    public function index($id){

        $warehouse = WareHouse::where('id','=',$id)->get()->first();
        $products = Products::where('wh_id','=',$id)->get();
        return view('warehouse')->with('products',$products)
                                ->with('warehouse',$warehouse);
    }

    /**
     * Render Edit Warehouse Form View
     * @param $id
     * @return $this
     */
    public function editWareHouse($id){

        $warehouse = WareHouse::where('id','=',$id)->get()->first();
        return view('edit_warehouse')->with('warehouse',$warehouse);
    }

    /**
     * Update WareHouse
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updateWarehouse(Request $request){
        $id = $request->input('id');
        $name = $request->input('name');
        $location = $request->input('location');
        $owner = $request->input('owner');

        $update = WareHouse::where('id','=',$id)
                            ->update([
                                'name'      =>  $name,
                                'location'  =>  $location,
                                'owner'     =>  $owner
                            ]);
        if($update){
            return redirect('/');
        }
    }

    /**
     * Render New Product Form
     * @param $id
     * @return $this
     */
    public function newProduct($id){
        return view('new_product')->with('id',$id);
    }

    /**
     * Check if Product Exists with warehouse id and name
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function productExist(Request $request){
        $id = $request->input('wh_id');
        $name = $request->input('name');

        $p_count = Products::where('wh_id','=',$id)
                            ->where('name','=',$name)
                            ->count();

        if($p_count){
            return response()->json(true);
        }
        return response()->json(false);
    }

    /**
     * Add New Product
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addProduct(Request $request){

        $wh_id = $request->input('id');
        $name = $request->input('p_name');
        $price = $request->input('p_price');
        $r_price = $request->input('p_r_price');
        $quantity = $request->input('p_quantity');

        $product = new Products;
        $product->name = $name;
        $product->wh_id = $wh_id;
        $product->price = $price;
        $product->retail_price = $r_price;
        $product->quantity = $quantity;

        if($product->save()){
            return response()->json(true);
        }
        return response()->json(false);
    }

    /**
     * Render Edit Product Form View
     * @param $wh_id
     * @param $p_id
     * @return mixed
     */
    public function editProduct($wh_id, $p_id){
        $warehouse = WareHouse::where('id','=',$wh_id)->get()->first();
        $product = Products::where('wh_id','=',$wh_id)
                            ->where('id','=',$p_id)
                            ->get()
                            ->first();
        return view('edit_product')
                    ->with('product',$product)
                    ->with('warehouse',$warehouse);
    }

    /**
     * Update Product
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProduct(Request $request){
        $p_id = $request->input('p_id');
        $wh_id = $request->input('id');
        $name = $request->input('p_name');
        $price = $request->input('p_price');
        $r_price = $request->input('p_r_price');
        $quantity = $request->input('p_quantity');

        $update = Products::where('wh_id', '=', $wh_id)
                            ->where('id','=', $p_id)
                            ->update([
                                'name'  =>  $name,
                                'price' =>  $price,
                                'retail_price'  =>  $r_price,
                                'quantity'  =>  $quantity
                            ]);
        if($update){
            return response()->json(true);
        }
        return response()->json(false);
    }

    /**
     * Delete Products
     * @param $wh_id
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function deleteProduct($wh_id, $id){
        $product = Products::where('id','=',$id);

        if($product->delete()){
            return redirect('/warehouse/'.$wh_id.'/products');
        }
    }
}