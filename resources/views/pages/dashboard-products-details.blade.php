@extends('layouts.dashboard')

@section('title')
 Store Dashboard Products Details
@endsection
@section('content')
<div
            class="section-content section-dashboard-home"
            data-aos="fade-up"
          >
        <div class="container-fluid">
            <div class="dashboard-heading">
            <h2 class="dashboard-title">{{ $product->name }}</h2>
            <p class="dashboard-subtitle">Product Details</p>
            </div>
        </div>
        <div class="dashboard-content">
            <div class="row">
            <div class="col-12">
                  @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                <form action="{{ route('dashboard-products-update',$product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" value="{{ $product->users_id }}" name="users_id">
                <div class="card">
                    <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Product Name</label>
                            <input
                            type="text"
                            name="name"
                            class="form-control"
                            value="{{ $product->name }}"
                            />
                        </div>
                        </div>
                        <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Price</label>
                            <input type="number" name="price" class="form-control" required value="{{$product->price}}">
                        </div>
                        </div>
                        <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Kategori</label>
                            <select
                            name="categories_id"
                            id="categories_id"
                            class="form-control"
                            >
                            <option value="{{ $product->categories_id }}">Tidak diganti "{{ $product->category->name }}"</option>
                            @foreach ($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                            </select>
                        </div>
                        </div>
                        <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Description</label>
                            {{-- memakai !! karena data berupa html !! --}}
                            <textarea name="description" id="editor">{!!$product->description!!}</textarea>
                        </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col text-right mt-2">
                        <button
                            type="submit"
                            class="btn btn-success px-5 btn-block"
                        >
                            Update Product
                        </button>
                        </div>
                    </div>
                    </div>
                </div>
                </form>
            </div>
            </div>
            <div class="row">
            <div class="col-12">
                <div class="card">
                <div class="card-body">
                    <div class="row">
                    @foreach ($product->galleries as $gallery)
                          <div class="col-md-4">
                            <div class="gallery-container">
                            <img
                            {{-- ?? jika tidak ada kosongi --}}
                                src="{{ Storage::url($gallery->photo ?? '') }}"
                                alt=""
                                class="w-100"
                            />
                            <a href="{{ route('dashboard-products-gallery-delete',$gallery->id) }}" class="delete-gallery">
                                <img
                                src="/images/products/tombol-delete.svg"
                                alt=""
                                />
                            </a>
                            </div>
                        </div>   
                    @endforeach

                    <div class="col-12">
                        <form action="{{ route('dashboard-products-gallery-upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                            <input type="hidden" name="products_id" value="{{ $product->id }}">
                            <input
                            type="file"
                            name="photo"
                            id="file"
                            style="display: none"
                            onchange="form.submit()"
                            />
                            <button
                            type="button"
                            class="btn btn-secondary btn-block mt-2"
                            {{-- fungsi untuk mengganti elemen saat di klik --}}
                            onclick="thisFileUpload()"
                            >
                            Add Photo
                            </button>
                        </form>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>
        </div>
@endsection

@push('addon-script')
      <script src="https://cdn.ckeditor.com/4.15.1/standard/ckeditor.js"></script>
    <script>
      function thisFileUpload() {
        document.getElementById("file").click();
      }
    </script>
    <script>
      CKEDITOR.replace("editor");
    </script>
@endpush