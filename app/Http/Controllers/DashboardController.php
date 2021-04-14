<?php

namespace App\Http\Controllers;

use App\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use PhpParser\Node\Stmt\Foreach_;

class DashboardController extends Controller
{
    public function index(){
        // mengambil data transactiondetail dengan relasi user (transaction) dan galleries (product) berdasarkan relasi product (transactiondetail) yang dimiliki oleh user yang sedang login
        $transaction = TransactionDetail::with(['transaction.user','product.galleries'])
                        ->whereHas('product',function($product){
                            $product->where('users_id',Auth::user()->id);
                        });

        // menghitung jumlah revenue 
        $revenue = $transaction->get()->reduce(function($carry,$item){
            return $carry + $item->price;
        });

    
        
        $customer = User::count();
        
        return view('pages.dashboard',[
            'transaction_count' =>$transaction->count(),
            'transaction_data' => $transaction->get(),
            'revenue' => $revenue,
            'customer' => $customer
        ]);
    }
}
