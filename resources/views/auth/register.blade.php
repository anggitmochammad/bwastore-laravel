@extends('layouts.auth')

@section('title')
    Register
@endsection

@section('content')

<div class="page-content page-auth" id="register">
    <div class="section-store-auth" data-aos="fade-up">
    <div class="container">
        <div class="row align-items-center justify-content-center row-login">
        <div class="col-lg-4">
            <h2>
            Memulai untuk jual beli <br />
            dengan cara terbaru
            </h2>
            <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label for="">Full Name</label>
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
            </div>
            <div class="form-group">
                <label for="">Email Address</label>
                <input id="email" 
                {{-- berfungsi untuk mengecek email saat field diisi --}}
                @change ="checkForEmail"
                {{-- berfungsi jika email_unavailable bernilai true maka class is-invalid akan berfungsi --}}
                :class="{'is-invalid' : this.email_unavailable}"
                v-model="email"
                type="email" 
                class="form-control @error('email') is-invalid @enderror" 
                name="email" 
                value="{{ old('email') }}" 
                required 
                autocomplete="email">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
            </div>
            <div class="form-group">
                <label for="">Password</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="">Konfirmasi Password</label>
                <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password">
                @error('password_confirmation')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            {{-- jika iya akan membuka tampilan nama toko dan kategory --}}
            <div class="form-group">
                <label for="">Store</label>
                <p class="text-muted">Apakah anda juga ingin membuka toko?</p>
                <div
                class="custom-control custom-radio custom-control-inline"
                >
                <input
                    type="radio"
                    class="custom-control-input"
                    name="is_store_open"
                    id="openStoreTrue"
                    v-model="is_store_open"
                    :value="true"
                />
                <label for="openStoreTrue" class="custom-control-label"
                    >Iya, boleh</label
                >
                </div>
                <div
                class="custom-control custom-radio custom-control-inline"
                >
                <input
                    type="radio"
                    class="custom-control-input"
                    name="is_store_open"
                    id="openStoreFalse"
                    v-model="is_store_open"
                    :value="false"
                />
                <label for="openStoreFalse" class="custom-control-label"
                    >Enggak, Makasih</label
                >
                </div>
            </div>
            
            {{-- jika is_store_open bernilai true maka dia akan muncul pakai (v-if) --}}
            <div class="form-group" v-if="is_store_open">
                <label for="">Nama Toko</label>
                <input
                type="text"
                id="store_name"
                name="store_name"
                class="form-control @error('password_confirmation') is-invalid @enderror"
                v-model="store_name"
                required
                autofocus
                autocomplete
                />
                @error('store_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group" v-if="is_store_open">
                <label for="categories_id">Categori</label>
                <select name="categories_id" id="categories_id" class="form-control">
                <option value="" disabled>Select Category</option>
                @foreach ($categories as $category)
                <option value="{{$category->id}}" >{{$category->name}}</option>
                @endforeach
                </select>
            </div>
            <button type="submit" 
                    class="btn btn-success btn-block mt-4"
                    {{-- berfungsi jika email_unavailable bernilai true maka tombol button disable --}}
                    :disable="this.email_unavailable"
                >Sign Up Now</button
            >
            <a href="{{route('login')}}" class="btn btn-signup btn-block mt-2"
                >Back to Sign Up</a
            >
            </form>
        </div>
        </div>
    </div>
    </div>
</div>
@endsection

@push('addon-script')
    <script src="/vendor/vue/vue.js"></script>
    <script src="https://unpkg.com/vue-toasted"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    
    <!-- memanggil vue toasted -->
    <script>
      Vue.use(Toasted);
      var register = new Vue({
        el: "#register",
        mounted() {
          AOS.init();
        },
        // membuat fungsi checkForEmail
        methods: {
            checkForEmail: function(){
                // memindahkan function ke variabel self
                var self = this;
                axios.get('{{ route('api-register-check') }}', {
                    // parameter sebagai objek yang berfungsi memanggil email yang ada di parameter
                    params: {
                        email: this.email
                    }
                })
                .then(function (response) {
                    // Memeriksa respon data yang dihasilkan oleh route('api-register-check')
                    if(response.data == 'Available'){
                        self.$toasted.show(
                            " Email anda tersedia ! Silahkan lanjut",
                            {
                            position: "top-center",
                            className: "rounded",
                            duration: 1000,
                            }
                        );
                        // menentukan hasil email_unavailable yang akan digunakan untuk disable button dan munculkan kelas is-invalid jika nilainya true
                        self.email_unavailable = false;
                    }else{
                        self.$toasted.error(
                            " Maaf, tampaknya email sudah terdaftar pada sistem kami.",
                            {
                            position: "top-center",
                            className: "rounded",
                            duration: 1000,
                            }
                        );
                        // menentukan hasil email_unavailable yang akan digunakan untuk disable button dan munculkan kelas is-invalid jika nilainya true
                        self.email_unavailable = true;
                    }
                    // handle success
                    console.log(response);
                });
            }

        },
        data(){
            return{
                name: "",
                email: "",
                is_store_open: "",
                store_name: "",
                email_unavailable: false
            }
        },
      });
    </script>
@endpush
