<?php namespace App\Http\Controllers;



use App\Models\Receipt;
use App\Models\Transaction;

class PrinterController extends Controller{

    public function receipt($receipt_id){
        $receipt = Receipt::where('id','=',$receipt_id)->get()->first();
        $transactions = Transaction::where('receipt_id','=',$receipt_id)->get();

        return view('receipt_print')
                ->with('receipt', $receipt)
                ->with('transactions',$transactions);
    }
}