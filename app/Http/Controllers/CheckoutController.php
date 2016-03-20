<?php namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Receipt;
use App\Models\Transaction;
use App\Models\WareHouse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{

    public function index($id)
    {
        $warehouse = WareHouse::where('id', '=', $id)->get();
        if (count($warehouse)) {
            $warehouse = $warehouse->first();
            return view('checkout')->with('warehouse', $warehouse);
        }
        return redirect('/');
    }

    public function getProducts($id)
    {
        $products = Products::where('wh_id', '=', $id);
        return response()->json($products->lists('name'));
    }

    public function getProductDetails(Request $request)
    {
        $wh_id = $request->input('wh_id');
        $name = $request->input("name");
        $product = Products::where('wh_id', '=', $wh_id)
                            ->where('name', '=', $name)
                            ->get()
                            ->first();
        return response()->json($product);
    }

    public function validateQuantity(Request $request)
    {
        $wh_id = $request->input('wh_id');
        $name = $request->input('name');

        $quantity = Products::where('wh_id', '=', $wh_id)
                            ->where('name', '=', $name)
                            ->get()
                            ->first()->quantity;
        return response()->json($quantity);
    }

    public function checkout(Request $request)
    {
        $wh_id = $request->input('wh_id');
        $items = json_decode($request->input('items'));
        $buyer = $request->input('buyer');
        $address = $request->input('address');
        $phone = $request->input('phone');
        $receipt_id = ($this->getMaxReceiptCount()) ? $this->getMaxReceiptCount() + 1 : 1;
        $this->createReceipt($receipt_id, $wh_id, $buyer, $address, $phone);

        foreach ($items as $item) {
            $this->decrementProductCount($wh_id, $item->name, $item->quantity);
            $transaction = new Transaction;
            $transaction->wh_id = $wh_id;
            $transaction->receipt_id = $receipt_id;
            $transaction->item_name = $item->name;
            $transaction->item_quantity = $item->quantity;
            $transaction->retail_price = $item->r_price;
            $transaction->unit_price = $item->unit_price;
            $transaction->retail_total = $item->quantity * $item->r_price;
            $transaction->cost_total = $item->quantity * $item->unit_price;
            $transaction->save();
        }
        return response()->json($this->getMaxReceiptCount());
    }

    private function createReceipt($receipt_id, $wh_id, $buyer, $address, $phone)
    {
        $receipt = new Receipt;
        $receipt->id = $receipt_id;
        $receipt->wh_id = $wh_id;
        $receipt->name = $buyer;
        $receipt->address = $address;
        $receipt->phone_no = $phone;

        if ($receipt->save()) {
            return true;
        }
        return false;
    }

    private function decrementProductCount($wh_id, $item_name, $item_quantity){
        Products::where('wh_id', '=', $wh_id)->where('name', '=', $item_name)->decrement('quantity', $item_quantity);
    }

    public function getMaxReceiptCount()
    {
        return Receipt::max('id');
    }
}