<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\CategoryRequest;
use Illuminate\Support\Str;

class CategoryController extends Controller
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
            // mengambil query dari model category
            $query = Category::query();
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
                                <a class="dropdown-item" href="'.route('category.edit',$item->id).'">
                                    Sunting
                                </a>
                                <form action="'.route('category.destroy',$item->id).'" method ="POST">
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
            // untuk mengedit column photo agar urlnya lengkap
            ->editColumn('photo',function($item){
                // mengecek apakah foto ada atau tidak jika ada tangkap urlnya
                return $item->photo ? '<img src="'.Storage::url($item->photo).'" style= "max-height : 40px;"/>':'';
            })
            // agar bisa terender 
            ->rawColumns(['action','photo'])
            ->make();
        }
        
        return view('pages.admin.category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view('pages.admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $data = $request->all();
        // fungsi slug agar pada url bisa rapi dan kelihatan jika searching (coba liat toko pedia)
        $data['slug'] = Str::slug($request->name);
        // jangan lupa php artisan storage:link
        // ditaruh di folder public/assets/category
        $data['photo'] = $request->file('photo')->store('assets/category','public');

        // memasukkan database
        Category::create($data);
        return redirect()->route('category.index');
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
        $item = Category::findOrFail($id);
        return view('pages.admin.category.edit',compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, $id)
    {
        $data = $request->all();
        // fungsi slug agar pada url bisa rapi dan kelihatan jika searching (coba liat toko pedia)
        $data['slug'] = Str::slug($request->name);
        // jangan lupa php artisan storage:link
        $data['photo'] = $request->file('photo')->store('assets/category','public');

        $item = Category::findOrFail($id);
        $item->update($data);
        return redirect()->route('category.index');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Category::findOrFail($id);
        $item->delete();

        return redirect()->route('category.index');

    }
}
