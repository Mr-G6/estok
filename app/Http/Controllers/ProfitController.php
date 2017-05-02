<?php namespace App\Http\Controllers;


use App\Inventory;
use App\Transaction;
use App\WareHouse;
use Illuminate\Http\Request;

class ProfitController extends Controller{

    public function index($inventory_id){
        $inventory = Inventory::where('id','=',$inventory_id)->get()->first();
        $transactions = Transaction::where('inventory_id','=',$inventory_id)->orderBy('created_at','DESC')->paginate(50);
        return view('profit')
                    ->with('transactions', $transactions)
                    ->with('inventory', $inventory);
    }

    public function getTransactionsByDate($inventory_id, Request $request){
        $from = $request->input('from').' 00:00:00';
        $to = $request->input('to').' 23:59:59';

        $transactions = Transaction::where('inventory_id','=',$inventory_id)
                                    ->where('created_at','>=',$from)
                                    ->where('created_at','<=',$to)
                                    ->orderBy('created_at','DESC')->get();

        return response()->json($transactions);
    }

    public function getTransactionsById($inventory_id , Request $request){
        $id = $request->input('id');

        $transactions = Transaction::with('product')->where('product_id','=', $id)
                                    ->where('inventory_id','=',$inventory_id)
                                    ->orderBy('created_at','DESC')->get();

        return response()->json($transactions);
    }

    public function getTransactionsByName($inventory_id , Request $request){
        $name = $request->input('name');

        $transactions = Transaction::where('product_name', 'LIKE', '%'.$name.'%')->get();

        return response()->json($transactions);
    }
}