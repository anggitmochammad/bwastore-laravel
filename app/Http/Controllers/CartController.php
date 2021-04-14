<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Cart;

class CartController extends Controller 
{
   /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        // mengambil card dengan relasi galleries & user di model product dan user & product di model cart
        $carts = Cart::with(['product.galleries','user','product','product.user'])
                        ->where('users_id',Auth::user()->id)
                        ->get();

        return view('pages.cart',compact('carts','user'));
    }
    public function delete(Request $request, $id)
    {
        $cart = Cart::findOrFail($id);
        $cart->delete();

        return redirect()->route('cart');
    }

    public function success()
    {
        return view('pages.success');
    }
}
