<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\User;
use App\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\ProductGalleryRequest;
use App\ProductsGallery;
use Illuminate\Support\Str;

class ProductGalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // mengecek apakah ada request dari ajax
        if(request()->ajax())
        {
            // mengambil function relasi yang ada di model product
            $query = ProductsGallery::with(['product']);
            // membuat data tables
            return Datatables::of($query)
            // membuat kolom dengan nama action
            // $item diambil dari controller
            ->addColumn('action',function($item){
                return '
                    <div class="btn-group">
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle mr-1 mb-1" type="button" data-toggle="dropdown">
                                Aksi
                            </button>
                            <div class="dropdown-menu">
                                
                                <form action="'.route('product-gallery.destroy',$item->id).'" method ="POST">
                                '.method_field('delete'). csrf_field().'
                                    <button type ="submit" class="dropdown-item text-danger">
                                        Hapus
                                    </button>
                                </form>  
                            </div>
                        </div>
                    </div>
                    ';
            })
            ->editColumn('photo',function($item){
                // mengecek menggunakan operator tenary apakah terdapat photo atau tidak
                // jika gambar ada tampilkan gambar
                return $item->photo ? '<img src="'.Storage::url($item->photo).'" style="max-height:88px"/>' : '';
            })
            // agar bisa terender 
            ->rawColumns(['action','photo'])
            ->make();
        }
        
        return view('pages.admin.product-gallery.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       $products = Product::all();
       return view('pages.admin.product-gallery.create',compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductGalleryRequest $request)
    {
        $data = $request->all();
        // jangan lupa php artisan storage:link
        // ditaruh di folder public/storage/assets/product
        $data['photo'] = $request->file('photo')->store('assets/product','public');
        // memasukkan database
        ProductsGallery::create($data);
        return redirect()->route('product-gallery.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductGalleryRequest $request, $id)
    {
       
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = ProductsGallery::findOrFail($id);
        $item->delete();

        return redirect()->route('product-gallery.index');

    }
}
