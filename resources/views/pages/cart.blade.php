@extends('layouts.app')

@section('title')
    Store Cart Page
@endsection

@section('content')
<div class="page-content page-cart">
    <section
    class="store-breadcrumbs"
    data-aos="fade down"
    data-aos-delay="100"
    >
    <div class="container">
        <div class="row">
        <div class="col-12">
            <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                <a href="{{route('home')}}">Home</a>
                </li>
                <li class="breadcrumb-item active">cart</li>
            </ol>
            </nav>
        </div>
        </div>
    </div>
    </section>
    <section class="store-cart">
    <div class="container">
        <div class="row" data-aos="fade-up" data-aos-delay="100">
        <div class="col-12 table-responsive">
            <table class="table table-borderless table-cart">
            <thead>
                <tr>
                <td>Image</td>
                <td>Name &amp; Seller</td>
                <td>Price</td>
                <td>Menu</td>
                </tr>
            </thead>
            <tbody>
            @php
            // untuk harga
                $totalPrice = 0;
            @endphp
            @foreach ($carts as $cart)    
            <tr>
                <td style="width: 20%">
                    <img
                    {{-- mengambil kolom foto dengan relasi di model product --}}
                    src="{{ Storage::url($cart->product->galleries->first()->photo) }}"
                    alt=""
                    class="cart-image"
                    />
                </td>
                <td style="width: 35%">
                    <div class="product-title">{{ $cart->product->name }}</div>
                    <div class="product-subtitle">{{ $cart->product->user->store_name}}</div>
                </td>
                <td style="width: 25%">
                    <div class="product-title">${{ number_format($cart->product->price) }}</div>
                    <div class="product-subtitle">USD</div>
                </td>
                <td style="width: 25%">
                    <form action="{{ route('cart-delete',$cart->id) }}" method="POST">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-remove-cart">
                            Remove
                        </button>
                    </form>
                </td>
            </tr>
            @php
                $totalPrice += $cart->product->price;
            @endphp
            @endforeach

            </tbody>
            </table>
        </div>
        </div>
        <div class="row" data-aos="fade-up" data-aos-delay="150">
            <div class="col-12">
                <hr />
            </div>
            <div class="col-12">
                <h2 class="mb-4">Shipping Details</h2>
            </div>
        </div>
        <form action="{{ route('checkout') }}" id="locations" method="POST" enctype="multipart/form-data">
        @csrf
        {{-- mengambil data dari form atas ^ --}}
        <input type="hidden" name="total_price" value="{{$totalPrice}}">
        <div class="row mb-2" data-aos="fade-up" data-aos-delay="200">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="anddress_one">Address 1</label>
                    <input
                        type="text"
                        class="form-control"
                        id="anddress_one"
                        name="anddress_one"
                        {{-- ?? = jika tidak ada kosongi --}}
                        value="{{ $user->anddress_one ?? '' }}"
                    />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                <label for="anddress_two">Address 2</label>
                <input
                    type="text"
                    class="form-control"
                    id="anddress_two"
                    name="anddress_two"
                    {{-- ?? = jika tidak ada kosongi --}}
                    value="{{ $user->anddress_two ?? '' }}"
                />
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                <label for="provinces_id">Province</label>
                {{-- kondisional menggunakan v-if --}}
                <select class="form-control" id="provinces_id" name="provinces_id" v-if="provinces" v-model="provinces_id">
                    {{-- melakukan perulangan menggunakan v-for dengan data provinces menjadi province --}}
                    {{-- harus ditambahkan @ karena biar tidak terbaca sebagai output dari blade --}}
                    <option v-for="province in provinces" :value="province.id">@{{ province.name }}</option>
                </select>
                {{-- untuk select v-else--}}
                <select v-else class="form-control"></select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                <label for="regencies_id">City</label>
                    {{-- kondisional menggunakan v-if --}}
                    <select class="form-control" id="regencies_id" name="refencies_id" v-if="regencies" v-model="regencies_id">
                        {{-- melakukan perulangan menggunakan v-for dengan data regencies menjadi regency --}}
                        {{-- harus ditambahkan @ karena biar tidak terbaca sebagai output dari blade --}}
                        <option v-for="regency in regencies" :value="regency.id">@{{ regency.name }}</option>
                    </select>
                    {{-- untuk select v-else--}}
                    <select v-else class="form-control"></select>
                    </div>
                </div>
            <div class="col-md-4">
                <div class="form-group">
                <label for="zip_code">Postal Code</label>
                {{-- ?? = jika tidak ada kosongi --}}
                <input class="form-control" type="text" name="zip_code" id="zip_code" value="{{ $user->zip_code ?? '' }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                <label for="country">Country</label>
                {{-- ?? = jika tidak ada kosongi --}}
                <input
                    type="text"
                    class="form-control"
                    id="country"
                    name="country"
                    value="{{ $user->country ?? '' }}"
                />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                <label for="phone_number">Mobile</label>
                <input
                {{-- ?? = jika tidak ada kosongi --}}
                    type="text"
                    class="form-control"
                    id="phone_number"
                    name="phone_number"
                    value="{{ $user->phone_number ?? '' }}"
                />
                </div>
            </div>
            </div>
            <div class="row" data-aos="fade-up" data-aos-delay="150">
            <div class="col-12">
                <hr />
            </div>
            <div class="col-12">
                <h2 class="mb-4">Payment Informations</h2>
            </div>
            </div>
            <div class="row" data-aos="fade-up" data-aos-delay="200">
                <div class="col-4 col-md-2">
                    <div class="product-title">$10</div>
                    <div class="product-subtitle">Country Tax</div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="product-title">$280</div>
                    <div class="product-subtitle">Product Insurance</div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="product-title">$580</div>
                    <div class="product-subtitle">Ship to Jakarta</div>
                </div>
                <div class="col-4 col-md-3">
                        {{-- jika tidak ada isinya akan default 0 sehingga tidak error --}}
                    <div class="product-title text-success">${{ number_format($totalPrice ?? 0) }}</div>
                    <div class="product-subtitle">Total</div>
                </div>
                <div class="col-8 col-md-3">
                    <button type="submit" class="btn btn-success mt-4 px-4 btn-block">Checkout Now</button>
                </div>
            </div>
        </form> 
        </div>
    </div>
        
</div>
@endsection

@push('addon-script')
    <script src="/vendor/vue/vue.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
      // menginisialisasi vue
      var locations = new Vue({
        // tag locations berdasarkan id
        el: "#locations",
        // skrip yang dijalankan saat vue muncul pada saat pertama web dibuka
        mounted() {
          AOS.init();
          this.getProvincesData();
        },
        // data untuk foto
        data: {
        // menyimpan data
          provinces: null,
          regencies: null,
        //   menyimpan id
          provinces_id: null,
          refencies_id: null

        },
        // fungsi yang digunakan untuk mengganti foto yang aktif
        methods: {
        // fungsi untuk mengambil data provinsi
          getProvincesData(){
            // memindahkan function ke variabel self
            var self = this;
            axios.get('{{ route('api-provinces') }}')
                .then(function(response){
                    // mengambil data dari response route dimasukkan ke provinces
                    self.provinces = response.data;
                })
            
          },
          // fungsi untuk mengambil data regenci/kota
          getRegenciesData(){
            // memindahkan function ke variabel self
            var self = this;
            // mengambil dari url lalu ditambahkan provinces_id dari data
            axios.get('{{ url('api/regencies') }}/'+self.provinces_id)
                .then(function(response){
                    // mengambil data dari response url lalu dimasukkan ke regencies
                    self.regencies = response.data;
                })
          }
        },
        // Berfungsi untuk melihat perubahan jika provinces_id berubah maka regencies_id juga berubah mengikutinya
        watch: {
            provinces_id: function(val, oldVal){
                // regencies_id akan dikosongkan dahulu lalu menjalankan function getRegenciesData()
                this.regencies_id = null;
                this.getRegenciesData();
            }
        }
      });
    </script>
@endpush