<?php namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\Transaction;
use App\Models\WareHouse;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index($wh_id){
        $warehouse = WareHouse::where('id','=',$wh_id)->get()->first();
        $receipts = Receipt::where('wh_id','=',$wh_id)->orderBy('id','DESC')->paginate(30);


        for($i = 0; $i < count($receipts); $i++){
            $receipts[$i]->total_items = Transaction::where('receipt_id', '=', $receipts[$i]->id)
                                                    ->sum('item_quantity');

            $receipts[$i]->total_retail = Transaction::where('receipt_id', '=', $receipts[$i]->id)
                ->sum('retail_total');

            $receipts[$i]->cost_total = Transaction::where('receipt_id', '=', $receipts[$i]->id)
                ->sum('cost_total');

            $receipts[$i]->profit = $receipts[$i]->total_retail - $receipts[$i]->cost_total;
        }

        return view('receipts')->with('receipts', $receipts)
                                ->with('warehouse',$warehouse);
    }

    public function getSales(Request $request){
        $name = $request->input('receipt_id');

        $transactions = Transaction::where('receipt_id', '=', $name)
                                    ->orderBy('created_at','DESC')
                                    ->get();
        return response()->json($transactions);
    }

    public function getSalesByIdOrName(Request $request){
        $name = $request->input('query');
        $wh_id = $request->input('wh_id');

        $receipts = Receipt::where('id', '=', $name)
                                ->orWhere('name','LIKE','%'.$name.'%')
                                ->orderBy('created_at','DESC')
                                ->get();

        for($i = 0; $i < count($receipts); $i++){
            $receipts[$i]->total_items = Transaction::where('receipt_id', '=', $receipts[$i]->id)
                ->sum('item_quantity');

            $receipts[$i]->total_retail = Transaction::where('receipt_id', '=', $receipts[$i]->id)
                ->sum('retail_total');

            $receipts[$i]->cost_total = Transaction::where('receipt_id', '=', $receipts[$i]->id)
                ->sum('cost_total');

            $receipts[$i]->profit = $receipts[$i]->total_retail - $receipts[$i]->cost_total;
        }

        return response()->json($receipts);
    }

    public function deleteSales(Request $request){
        $receipt_id = $request->input('receipt_id');

        $receipts = Receipt::where('id','=',$receipt_id);
        $transactions = Transaction::where('receipt_id','=',$receipt_id);

        if($receipts->delete() && $transactions->delete()){
            return response()->json(true);
        }
        return response()->json(false);
    }
}