<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\ProductRequest;
use App\ProductsGallery;

class DashboardProductsController extends Controller
{
   public function index()
   {
       $products = Product::with(['category','galleries','user'])->where('users_id',Auth::user()->id)->get();
       return view('pages.dashboard-products',compact('products'));
   }
   public function details(Request $request, $id){

       $product = Product::with(['galleries','user','category'])->findOrFail($id);
       $categories = Category::all();
       return view('pages.dashboard-products-details',compact('product','categories'));
   }
   public function uploadGallery (Request $request){
       $data = $request->all();
        // jangan lupa php artisan storage:link
        // ditaruh di folder public/storage/assets/product
        $data['photo'] = $request->file('photo')->store('assets/product','public');
        // memasukkan database
        ProductsGallery::create($data);
        return redirect()->route('dashboard-products-details',$request->products_id);
   }

   public function deleteGallery (Request $request, $id){

     $item = ProductsGallery::findOrFail($id);
     $item->delete();
    return redirect()->route('dashboard-products-details',$item->products_id);
   }

   public function create(){
       $categories = Category::all();
       return view('pages.dashboard-products-create',compact('categories'));
   }

   public function store(ProductRequest $request)
    {
        $data = $request->all();       
        // memasukkan ke model product
        $data['slug'] = Str::slug($request->name);
        $product = Product::create($data);

        // memasukkan ke model productsgallery
        $gallery = [
            'products_id' => $product->id,
            // jangan lupa php artisan storage:link
            // ditaruh di folder public/storage/assets/product
            'photo' => $request->file('photo')->store('assets/product','public')
        ];
        ProductsGallery::create($gallery);
        
        return redirect()->route('dashboard-products');
    }

    public function update(ProductRequest $request, $id)
    {
        $data = $request->all();
        // fungsi slug agar pada url bisa rapi dan kelihatan jika searching (coba liat toko pedia)
        $data['slug'] = Str::slug($request->name);
        $item = Product::findOrFail($id);
        $item->update($data);
        return redirect()->route('dashboard-products');
        
    }

   
}
