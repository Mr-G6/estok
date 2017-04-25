<?php namespace App\Http\Controllers;

use App\Inventory;
use App\Products;
use App\Receipt;
use App\Transaction;
use App\WareHouse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{

    /**
     * Inventory Checkout
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($id)
    {
        $inventory = Inventory::where('id', '=', $id)->get();
        if (count($inventory)) {
            $inventory = $inventory->first();
            return view('checkout')->with('inventory', $inventory);
        }
        return redirect('/');
    }

    /**
     * Get available product names for autocomplete
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProducts($id)
    {
        $products = Products::where('inventory_id', '=', $id);
        return response()->json($products->pluck('name'));
    }

    public function getProductDetails(Request $request)
    {
        $inventory_id = $request->inventory_id;
        $name = $request->name;
        $product = Products::where('inventory_id', '=', $inventory_id)
                            ->where('name', '=', $name)
                            ->get()
                            ->first();
        return response()->json($product);
    }

    public function validateQuantity(Request $request)
    {
        $inventory_id = $request->inventory_id;
        $name = $request->name;

        $quantity = Products::where('inventory_id', '=', $inventory_id)
                            ->where('name', '=', $name)
                            ->get()
                            ->first()->quantity;
        return response()->json($quantity);
    }

    public function checkout(Request $request)
    {
        $inventory_id = $request->inventory_id;
        $items = json_decode($request->items);
        $buyer = $request->buyer;
        $address = $request->address;
        $phone = $request->phone;
        $paid = $request->paid;
        $unpaid = $this->getUnpaidAmount($request->paid, $items);
        $receipt_id = ($this->getMaxReceiptCount()) ? $this->getMaxReceiptCount() + 1 : 1;
        $this->createReceipt($receipt_id, $inventory_id, $buyer, $address, $phone, $paid, $unpaid);

        foreach ($items as $item) {
            $this->decrementProductCount($inventory_id, $item->name, $item->quantity);
            $transaction = new Transaction;
            $transaction->inventory_id = $inventory_id;
            $transaction->receipt_id = $receipt_id;
            $transaction->product_name = $item->name;
            $transaction->quantity = $item->quantity;
            $transaction->retail_price = $item->r_price;
            $transaction->unit_price = $item->unit_price;
            $transaction->retail_total = $item->quantity * $item->r_price;
            $transaction->cost_total = $item->quantity * $item->unit_price;
            $transaction->save();
        }
        return response()->json($this->getMaxReceiptCount());
    }

    public function getUnpaidAmount($paid, $items)
    {
        if ($paid) {
            return 0;
        }
        $total = 0;
        foreach ($items as $item) {
            $total += $item->quantity * $item->r_price;
        }
        return $total;
    }

    private function createReceipt($receipt_id, $inventory_id, $buyer, $address, $phone, $paid, $unpaid)
    {
        $receipt = new Receipt;
        $receipt->id = $receipt_id;
        $receipt->inventory_id = $inventory_id;
        $receipt->name = $buyer;
        $receipt->address = $address;
        $receipt->phone_no = $phone;
        $receipt->paid = $paid;
        $receipt->unpaid = $unpaid;

        if ($receipt->save()) {
            return true;
        }
        return false;
    }

    private function decrementProductCount($inventory_id, $item_name, $item_quantity)
    {
        Products::where('inventory_id', '=', $inventory_id)->where('name', '=', $item_name)->decrement('quantity', $item_quantity);
    }

    public function getMaxReceiptCount()
    {
        return Receipt::max('id');
    }
}