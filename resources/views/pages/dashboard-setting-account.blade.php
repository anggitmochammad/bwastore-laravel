@extends('layouts.dashboard')

@section('title')
Setting Account
@endsection

@section('content')
<div
            class="section-content section-dashboard-home"
            data-aos="fade-up"
          >
        <div class="container-fluid">
            <div class="dashboard-heading">
            <h2 class="dashboard-title">Account Settings</h2>
            <p class="dashboard-subtitle">Update your current profile</p>
            </div>
        </div>
        <div class="dashboard-content">
            <div class="row">
            <div class="col-12">
                <form action="{{ route('dashboard-setting-update','dashboard-setting-account') }}" method="POST" enctype="multipart/form-data" id="locations">
                @csrf
                <div class="card">
                    <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                        <div class="form-group">
                            <label for="yourName">Your Name</label>
                            <input
                            type="text"
                            class="form-control"
                            id="yourName"
                            name="name"
                            value="{{ $user->name }}"
                            />
                        </div>
                        </div>
                        <div class="col-md-6">
                        <div class="form-group">
                            <label for="yourEmail">Your Email</label>
                            <input
                            type="email"
                            class="form-control"
                            id="yourEmail"
                            name="email"
                            value="{{ $user->email }}"
                            />
                        </div>
                        </div>
                        <div class="col-md-6">
                        <div class="form-group">
                            <label for="addressOne">Address 1</label>
                            <input
                            type="text"
                            class="form-control"
                            id="addressOne"
                            name="anddress_one"
                            value="{{ $user->anddress_one }}"
                            />
                        </div>
                        </div>
                        <div class="col-md-6">
                        <div class="form-group">
                            <label for="addressTwo">Address 2</label>
                            <input
                            type="text"
                            class="form-control"
                            id="addressTwo"
                            name="anddress_two"
                            value="{{ $user->anddress_two }}"
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
                            <label for="postal-code">Postal Code</label>
                            <input class="form-control" type="text" name="zip_code" id="zip_code" value="{{ $user->zip_code }}">
                        </div>
                        </div>
                        <div class="col-md-6">
                        <div class="form-group">
                            <label for="country">Country</label>
                            <input
                            type="text"
                            class="form-control"
                            id="country"
                            name="country"
                            value="{{ $user->country }}"
                            />
                        </div>
                        </div>
                        <div class="col-md-6">
                        <div class="form-group">
                            <label for="mobile">Mobile</label>
                            <input
                            type="text"
                            class="form-control"
                            id="mobile"
                            name="phone_number"
                            value="{{ $user->phone_number }}"
                            />
                        </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col text-right mt-5">
                        <button type="submit" class="btn btn-success px-5">
                            Save Now
                        </button>
                        </div>
                    </div>
                    </div>
                </div>
                </form>
            </div>
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


