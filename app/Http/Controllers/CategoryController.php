<?php

namespace App\Http\Controllers;
use App\Product;
use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // mengambil data dari model product dengan relasi ke model productsgallery dengan 32 data 
        $products = Product::with('galleries')->paginate(10);
        $categories = Category::all();
        return view('pages.category',compact('products','categories'));
    }
    public function detail(Request $request, $slug)
    {
        $categories = Category::all();
        // berfungsi untuk mengambil data dari model category yang slug sama 
        $category = Category::where('slug',$slug)->firstOrFail();
        
        // mencari di tabel product yang categories_id sama
        $products = Product::with('galleries')->where('categories_id',$category->id)->paginate(32);

        return view('pages.category',compact('products','categories'));
    }
}
