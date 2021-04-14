@extends('layouts.admin')

@section('title')
 Gallery
@endsection
@section('content')
<div
    class="section-content section-dashboard-home"
    data-aos="fade-up"
    >
    <div class="container-fluid">
        <div class="dashboard-heading">
            <h2 class="dashboard-title">Gallery</h2>
            <p class="dashboard-subtitle">List Of Gallery</p>
        </div>
        <div class="dashboard-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <a href="{{route('product-gallery.create')}}" class="btn btn-primary mb-3">
                            + Tambah Galeri Baru
                            </a>
                            <div class="table-responsive">
                                <table class="table table-hover scroll-horizontal-vertical w-100" id="crudTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Produk</th>
                                            <th>Foto</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
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
    <script>
        // mengambil id
        var datatable = $('#crudTable').DataTable({
            processing: true,
            serverSide: true,
            ordering: true,
            ajax: {
                // mengarahkan url ke controller halaman itu sendiri dengan "current "
                // kurung kurawal escape
                url:'{!!url()->current()!!}' 
            },
            // mengisi kolom tabel 
            columns: [
                // data : dari database
                {data: 'id', name: 'id'},
                // mengambil relasi tabel product field name yang telah dibuat di model
                {data: 'product.name', name: 'product.name'},
                {data: 'photo', name: 'photo'},
                {
                    data: 'action', 
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    width: '15%'
                    },
            ]
        })
    </script>
@endpush