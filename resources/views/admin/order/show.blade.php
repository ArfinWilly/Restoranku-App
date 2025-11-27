@extends('admin.layouts.master')

@section('title', 'Detail Pesanan')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/admin/extensions/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/compiled/css/table-datatable.css') }}">
@endsection

@section('content')

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Detail Pesanan</h3>
                    <p class="text-subtitle text-muted">Informasi lengkap tentang pesanan</p>
                </div>
                {{-- <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <a href="{{ route('orders.create') }}" class="btn btn-primary float-start float-lg-end">
                                <i class="bi bi-plus-lg"></i>
                                Tambah Pesanan
                            </a>
                        </ol>
                    </nav>
                </div> --}}
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h4>Kode Pesanan: {{ $order->order_code }}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Dibuat Pada:</strong> {{ $order->created_at->format('d-m-Y H:i') }}</p>
                            <p><strong>Nama Pelanggan:</strong> {{ $order->user->fullname }}</p>
                            <p><strong>Status:</strong>
                                <span
                                    class="badge {{ $order->status == 'settlement' ? 'bg-success' : ($order->status == 'pending' ? 'bg-warning' : 'bg-primary') }}">
                                    {{ $order->status }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>No. Meja:</strong> {{ $order->table_number }}</p>
                            <p><strong>Metode Pembayaran:</strong> {{ $order->payment_method }}</p>
                            <p><strong>Catatan:</strong> {{ $order->notes ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h4>Daftar Pesanan yang Dipesan</h4>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <p><i class="bi bi-check-circle"></i> {{ session('success') }}</p>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Gambar</th>
                                <th>Nama Menu</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orderItems as $orderItem)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <img src="{{ asset('img_item_upload/' . $orderItem->item->img) }}" width="60"
                                            class="img-fluid rounded-top" alt=""
                                            onerror="this.onerror=null,this.src='{{ $orderItem->item->img }}' ;">
                                    </td>
                                    <td>{{ $orderItem->item->name }}</td>
                                    <td>{{ $orderItem->quantity }}</td>
                                    <td>{{ 'Rp' . number_format($orderItem->totalPrice, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tr>
                            <th colspan="4" class="text-end">Total:</th>
                            <th>{{ 'Rp' . number_format($order->subtotal, 0, ',', '.') }}</th>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-end">Pajak:</th>
                            <th>{{ 'Rp' . number_format($order->tax, 0, ',', '.') }}</th>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-end">Total Akhir:</th>
                            <th>{{ 'Rp' . number_format($order->grandTotal, 0, ',', '.') }}</th>
                        </tr>

                    </table>
                </div>
            </div>
        </section>
    </div>

@endsection

@section('script')

    <script src="{{ asset('assets/admin/extensions/simple-datatables/umd/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/admin/static/js/pages/simple-datatables.js') }}"></script>

@endsection
