<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\User;
use App\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\ProductRequest;
use Illuminate\Support\Str;

class ProductController extends Controller
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
            $query = Product::with(['user','category']);
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
                                <a class="dropdown-item" href="'.route('product.edit',$item->id).'">
                                    Sunting
                                </a>
                                <form action="'.route('product.destroy',$item->id).'" method ="POST">
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
            
            // agar bisa terender 
            ->rawColumns(['action'])
            ->make();
        }
        
        return view('pages.admin.product.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       $users = User::all();
       $categories = Category::all(); 
       return view('pages.admin.product.create',compact('users','categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $data = $request->all();
        
        $data['slug'] = Str::slug($request->name);
        
        // memasukkan database
        Product::create($data);
        return redirect()->route('product.index');
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
        // jika id tidak ada maka akan redirect ke halaman 404
        $item = Product::findOrFail($id);
        $users = User::all();
        $categories = Category::all(); 
        return view('pages.admin.product.edit',compact('item','users','categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        $data = $request->all();
        // fungsi slug agar pada url bisa rapi dan kelihatan jika searching (coba liat toko pedia)
        $data['slug'] = Str::slug($request->name);
        $item = Product::findOrFail($id);
        $item->update($data);
        return redirect()->route('product.index');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Product::findOrFail($id);
        $item->delete();

        return redirect()->route('product.index');

    }
}
