<?php

namespace App\Http\Controllers;

use App\Product;
use App\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardTransactionsController extends Controller
{
    public function index(){
        // mengambil data transactiondetail dengan (transaction) dan (product) berdasarkan relasi product (transactiondetail) yang dimiliki oleh user yang sedang login
        $sellTransaction = TransactionDetail::with(['transaction.user','product.galleries'])
                        ->whereHas('product',function($product){
                            $product->where('users_id',Auth::user()->id);
                        })->get();
        // mengambil data transactiondetail dengan (transaction) dan (product) berdasarkan relasi transaction (transactiondetail) yang dimiliki oleh user yang sedang login
        $buyTransaction = TransactionDetail::with(['transaction.user','product.galleries'])
                        ->whereHas('transaction',function($transaction){
                            $transaction->where('users_id',Auth::user()->id);
                        })->get(); 
        return view('pages.dashboard-transactions',compact('sellTransaction','buyTransaction'));
    }
    public function details(Request $request, $id){
        $transaction = TransactionDetail::with(['product.galleries','transaction.user'])->findOrFail($id);
        
        return view('pages.dashboard-transactions-details',compact('transaction'));
    }

    public function update(Request $request, $id){
        $data = $request->all();
        $item = TransactionDetail::findOrFail($id);
        $item->update($data);

        return redirect()->route('dashboard-transactions-details',$id);
    }
}
