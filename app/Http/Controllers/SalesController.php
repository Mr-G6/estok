<?php namespace App\Http\Controllers;

use App\Inventory;
use App\Receipt;
use App\Transaction;
use App\WareHouse;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    /**
     * Receipts
     * @param $inventory_id
     * @return \Illuminate\View\View
     */
    public function index($inventory_id){
        $inventory = Inventory::where('id','=',$inventory_id)->get()->first();
        $receipts = Receipt::where('inventory_id','=',$inventory_id)->orderBy('id','DESC')->paginate(30);


        for($i = 0; $i < count($receipts); $i++){
            $receipts[$i]->total_items = Transaction::where('receipt_id', '=', $receipts[$i]->id)
                                                    ->sum('quantity');

            $receipts[$i]->total_retail = Transaction::where('receipt_id', '=', $receipts[$i]->id)
                ->sum('retail_total');

            $receipts[$i]->cost_total = Transaction::where('receipt_id', '=', $receipts[$i]->id)
                ->sum('cost_total');

            $receipts[$i]->profit = $receipts[$i]->total_retail - $receipts[$i]->cost_total;
        }

        return view('receipts')->with('receipts', $receipts)
                                ->with('inventory',$inventory);
    }

    /**
     * Get receipt transactions
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSales(Request $request){
        $name = $request->input('receipt_id');

        $transactions = Transaction::where('receipt_id', '=', $name)
                                    ->orderBy('created_at','DESC')
                                    ->get();
        return response()->json($transactions);
    }

    /**
     * Sales search by name
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSalesByIdOrName(Request $request){
        $name = $request->input('query');
        $inventory_id = $request->input('inventory_id');

        $receipts = Receipt::where('id', '=', $name)
                                ->orWhere('name','LIKE','%'.$name.'%')
                                ->orderBy('created_at','DESC')
                                ->get();

        for($i = 0; $i < count($receipts); $i++){
            $receipts[$i]->total_items = Transaction::where('receipt_id', '=', $receipts[$i]->id)
                ->sum('quantity');

            $receipts[$i]->total_retail = Transaction::where('receipt_id', '=', $receipts[$i]->id)
                ->sum('retail_total');

            $receipts[$i]->cost_total = Transaction::where('receipt_id', '=', $receipts[$i]->id)
                ->sum('cost_total');

            $receipts[$i]->profit = $receipts[$i]->total_retail - $receipts[$i]->cost_total;
        }

        return response()->json($receipts);
    }

    /**
     * Delete reciept
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteSales(Request $request){
        $receipt = Receipt::find($request->receipt_id);

        if($receipt->delete()){
            return response()->json(true);
        }
        return response()->json(false);
    }

    /**
     * Get receipt details
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReceiptDetails(Request $request){
        $receipt = Receipt::find($request->id);
        return response()->json($receipt);
    }

    /**
     * Clear receipt record
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function clearDues(Request $request){
        $receipt = Receipt::find($request->id);

        $receipt->paid = 1;
        $receipt->unpaid = 0;

        if($receipt->save()){
            return response()->json(true);
        }
        return response()->json(false);
    }

    /**
     * Clear specified amount from receipt record
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function clearPartialDues(Request $request){
        $receipt = Receipt::find($request->id);

        $receipt->unpaid -= $request->amount;

        if($receipt->save()){
            return response()->json(true);
        }
        return response()->json(false);
    }
}