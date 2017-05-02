<?php

namespace App\Http\Controllers;

use App\Config;
use Validator;
use App\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){
        $inventories = Inventory::where('user_id','=', Auth::user()->id)->get();
        return view('dashboard')->with('inventories', $inventories);
    }

    public function getConfig(){
        return view('settings');
    }

    public function updateConfig(Request $request){
        if(Config::where('name','=', 'app_name')->update([
            'value' =>  $request->name
        ])){
            $request->session()->flash('message', 'Settings updated');
            return redirect('/settings');
        }

    }

    public function newInventory(){
        return view('add_inventory');
    }

    public function createInventory(Request $request){
        $validator = Validator::make($request->all(), [
            'inventory_name'    =>  'required'
        ]);
        if($validator->passes()){
            $inventory = new Inventory();
            $inventory->name = $request->inventory_name;
            $inventory->user_id = Auth::user()->id;

            if($inventory->save()){
                return redirect('/');
            }
        }
        return redirect('/add/inventory')->withErrors($validator);
    }

    public function deleteInventory(Request $request){
        $inventory = Inventory::find($request->id);

        if($inventory->delete()){
            return response()->json([
                'deleted'   =>  true
            ]);
        }

        return response()->json([
            'deleted'   =>  false
        ]);
    }

    public function logout(){
        Auth::logout();
        return redirect('/login');
    }


}
