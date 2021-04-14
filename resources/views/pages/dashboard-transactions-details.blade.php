@extends('layouts.dashboard')

@section('title')
 Store Dashboard Transactions Details
@endsection
@section('content')
<div
            class="section-content section-dashboard-home"
            data-aos="fade-up"
          >
    <div class="container-fluid">
        <div class="dashboard-heading">
        <h2 class="dashboard-title">#{{ $transaction->code }}</h2>
        <p class="dashboard-subtitle">Transactions Details</p>
        </div>
        <div class="dashboard-content" id="transactionDetails">
        <div class="row">
            <div class="col-12">
            <div class="card">
                <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-4">
                    <img
                    {{-- ambil data photo yang pertama jika tidak ada kosongkan --}}
                        src="{{ Storage::url($transaction->product->galleries->first()->photo ?? '') }}"
                        class="w-100 mb-3"
                        alt=""
                    />
                    </div>
                    <div class="col-12 col-md-8">
                    <div class="row">
                        <div class="col-12 col-md-6">
                        <div class="product-title">Customer Name</div>
                        <div class="product-subtitle">{{ $transaction->product->user->name }}</div>
                        </div>
                        <div class="col-12 col-md-6">
                        <div class="product-title">Product Name</div>
                        <div class="product-subtitle">
                            {{ $transaction->product->name }}
                        </div>
                        </div>
                        <div class="col-12 col-md-6">
                        <div class="product-title">
                            Date of Transaction
                        </div>
                        <div class="product-subtitle">
                            {{ $transaction->created_at }}
                        </div>
                        </div>
                        <div class="col-12 col-md-6">
                        <div class="product-title">Payment Status</div>
                        <div class="product-subtitle text-danger">
                            {{ $transaction->transaction->transaction_status }}
                        </div>
                        </div>
                        <div class="col-12 col-md-6">
                        <div class="product-title">Total Amount</div>
                        <div class="product-subtitle">${{ number_format($transaction->transaction->total_price) }}</div>
                        </div>
                        <div class="col-12 col-md-6">
                        <div class="product-title">Mobile</div>
                        <div class="product-subtitle">
                            {{ $transaction->product->user->phone_number }}
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                <form action="{{ route('dashboard-transactions-update',$transaction->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 mt-5">
                        <h5>Shipping Information</h5>
                        </div>
                        <div class="col-12">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="product-title">Address 1</div>
                                <div class="product-subtitle">
                                {{ $transaction->product->user->anddress_one }}
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="product-title">Address 2</div>
                                <div class="product-subtitle">
                                {{ $transaction->product->user->anddress_two }}
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="product-title">Province</div>
                                {{-- mengambil nama provinsi dengan id yang ada di user --}}
                                <div class="product-subtitle">{{ App\models\Province::find($transaction->product->user->provinces_id)->name }}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="product-title">City</div>
                                <div class="product-subtitle">{{ App\models\Regency::find($transaction->product->user->refencies_id)->name }} </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="product-title">Postal Code</div>
                                <div class="product-subtitle">{{ $transaction->product->user->zip_code}}</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="product-title">Country</div>
                                <div class="product-subtitle">{{ $transaction->product->user->country}}</div>
                            </div>
                            <div class="col-12 col-md-3">
                            <div class="product-title">Shipping Status</div>
                            <select
                                name="shipping_status"
                                id="status"
                                class="form-control"
                                v-model="status"
                            >
                                <option value="PENDING">Pending</option>
                                <option value="SHIPPING">Shipping</option>
                                <option value="SUCCESS">Success</option>
                            </select>
                            </div>
                            <!-- jika status == SHIPPING maka template muncul -->
                            <template v-if='status == "SHIPPING"'>
                                <div class="col-md-3">
                                    <div class="product-title">Input Resi</div>
                                    <input
                                    type="text"
                                    class="form-control"
                                    name="resi"
                                    v-model="resi"
                                    />
                                </div>
                                <div class="col-md-2">
                                    <button
                                    type="submit"
                                    class="btn btn-success mt-4 btn-block"
                                    >
                                    Update Resi
                                    </button>
                                </div>
                            </template>
                        </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12 text-right">
                        <button type="submit" class="btn btn-success">
                            Save Now
                        </button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection

@push('addon-script')
<script src="/vendor/vue/vue.js"></script>
<script>
    var transactionDetails = new Vue({
    // ditujukan ke transactiondetails
    el: "#transactionDetails",
    data: {
        status: "{{ $transaction->shipping_status }}",
        resi: "{{ $transaction->resi }}",
    },
    });
</script>
@endpush
