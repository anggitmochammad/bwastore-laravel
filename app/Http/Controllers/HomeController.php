<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Category;

class HomeController extends Controller
{


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // mengambil data dari model product dengan relasi ke model productsgallery dengan 8 data 
        $products = Product::with('galleries')->take(8)->get();
        // mengambil data model category hanya 6
        $categories = Category::take(6)->get();
        return view('pages.home',compact('products','categories'));
    }
}
