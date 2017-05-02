<?php

namespace App\Http\Controllers;

use App\Inventory;
use App\Products;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Render Warehouse Products View
     * @param $id
     * @return mixed
     */
    public function index($id){

        $inventory = Inventory::where('id','=',$id)->get()->first();

        return view('inventory')->with('inventory',$inventory);
    }

    /**
     * Render Edit Warehouse Form View
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editInventory($id){

        $inventory = Inventory::where('id','=',$id)->get()->first();
        return view('edit_inventory')->with('inventory',$inventory);
    }

    /**
     * Update WareHouse
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updateInventory(Request $request){
        $id = $request->input('id');
        $name = $request->input('name');

        $update = Inventory::where('id','=',$id)
            ->update([
                'name'      =>  $name,
            ]);
        if($update){
            return redirect('/');
        }
    }

    /**
     * Render New Product Form
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function newProduct($id){
        $inventory = Inventory::find($id);
        return view('new_product')->with('inventory', $inventory);
    }

    /**
     * Check if Product Exists with warehouse id and name
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function productExist(Request $request){
        $id = $request->input('inventory_id');
        $name = $request->input('name');

        $p_count = Products::where('inventory_id','=',$id)
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
        $retail_price = $request->input('retail_price');
        $unit_price = $request->input('unit_price');
        $quantity = $request->input('p_quantity');

        $product = new Products;
        $product->name = $name;
        $product->inventory_id = $wh_id;
        $product->unit_price = $unit_price;
        $product->quantity = $quantity;
        $product->retail_price = $retail_price;

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
        $warehouse = Inventory::where('id','=',$wh_id)->get()->first();
        $product = Products::where('inventory_id','=',$wh_id)
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
        $unit_price = $request->input('unit_price');
        $retail_price = $request->input('retail_price');
        $quantity = $request->input('p_quantity');

        $update = Products::where('inventory_id', '=', $wh_id)
            ->where('id','=', $p_id)
            ->update([
                'name'  =>  $name,
                'unit_price' =>  $unit_price,
                'retail_price'  =>  $retail_price,
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
    public function deleteProduct($inventory_id, $id){
        $product = Products::where('id','=',$id);

        if($product->delete()){
            return redirect('/inventory/'.$inventory_id.'/products');
        }
    }
}
