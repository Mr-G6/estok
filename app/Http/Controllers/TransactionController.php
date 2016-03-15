<?php namespace App\Http\Controllers;


use App\Models\Transaction;
use App\Models\WareHouse;
use Illuminate\Http\Request;

class TransactionController extends Controller{

    public function index($wh_id){
        $warehouse = WareHouse::where('id','=',$wh_id)->get()->first();
        $transactions = Transaction::where('wh_id','=',$wh_id)->orderBy('created_at','DESC')->paginate(50);
        return view('transactions')
                    ->with('transactions', $transactions)
                    ->with('warehouse', $warehouse);
    }

    public function getTransactionsByDate($wh_id, Request $request){
        $from = $request->input('from').' 00:00:00';
        $to = $request->input('to').' 23:59:59';

        $transactions = Transaction::where('wh_id','=',$wh_id)
                                    ->where('created_at','>=',$from)
                                    ->where('created_at','<=',$to)
                                    ->orderBy('created_at','DESC')->get();

        return response()->json($transactions);
    }
}