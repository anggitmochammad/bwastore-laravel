<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Cart;
use App\Transaction;
use App\TransactionDetail;

use Exception;

// midtrans
use Midtrans\Snap;
use Midtrans\Config;

class CheckoutController extends Controller
{
    public function process(Request $request)
    {
        // mengambil user yang login------------------------------------------
        $user = Auth::user();
        // update semua data field pada form kecuali total_price 
        $user->update($request->except('total_price'));

    // Proses checkout------------------------------------------
        // mengacak code 
        $code = 'STORE-'.mt_rand(0000,9999);
        // mengambil model cart dengan relasinya berdasarkan user yang login
        $carts = Cart::with(['product','user'])
                ->where('users_id', Auth::user()->id)
                ->get();
        
    // Transaction Create ------------------------------------------
        $transaction = Transaction::create([
            'users_id' => Auth::user()->id,
            'insurance_price' => 0,
            'shipping_price' => 0,
            'total_price' => $request->total_price,
            'transaction_status' => 'PENDING',
            'code' => $code
        ]);

    // Transaction Detail Create ------------------------------------------
        // Perulangan data carts 
        foreach ($carts as $cart){
            $trx = 'TRX-'. mt_rand(0000,9999);
            TransactionDetail::create([
                // mengambil dari variabel yang diatas
                'transactions_id' => $transaction->id,
                'products_id' => $cart->product->id,
                'shipping_status' => 'PENDING',
                'resi' => '',
                'code' => $trx,
                'price' => $cart->product->price
            ]);
        }
    // Cart Delete ------------------------------------------
    Cart::where('users_id',Auth::user()->id)->delete();

    // Konfigurasi Midtrans ------------------------------------------
        // mengambil dari config/service
        // Set your Merchant Server Key
        Config::$serverKey = config('services.midtrans.serverKey');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        Config::$isProduction = config('services.midtrans.isProduction');
        // Set sanitization on (default)
        Config::$isSanitized = config('services.midtrans.isSanitized');
        // Set 3DS transaction for credit card to true
        Config::$is3ds = config('services.midtrans.is3ds');

        // Membuat array untuk dikirim ke midtrans
        $midtrans = [
            'transaction_details' => [
                'order_id' => $code,
                // dibulatkan harus integer jika ada komanya nanti akan error
                'gross_amount' => (int) $request->total_price,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
            'enabled_payments' => [
                'gopay', 'indomaret','bank_transfer'
            ],
            'vtweb' => []
        ];

        // Midtrans snap redirect
       try {
            // Get Snap Payment Page URL
            $paymentUrl = Snap::createTransaction($midtrans)->redirect_url;
            
            // Redirect to Snap Payment Page
            return redirect($paymentUrl);
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }

    
        
    }



    public function callback(Request $request)
    {

    }
}
