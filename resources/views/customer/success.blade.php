@extends('customer.layouts.master')

@section('title' , 'Pesanan Berhasil')

@section('content')

<div class="container-fluid d-flex justify-content-center py-5">
    <div class="recip border p-4 bg-white shadow" style="width: 450px ; margin-top: 5rem">
        <h5 class="text-center mb-4">Pesanan Berhasil!</h5>

        @if ($order->payment_method == 'tunai' && $order->status == 'pending')
        <p class="text-center"><span class="badge bg-danger">Menunggu Pembayaran</span></p>
            
        @elseif ($order->payment_method == 'qris' && $order->status == 'pending')
            <p class="text-center"><span class="badge bg-success">Menunggu Konfirmasi Pembayaran</span></p>
            
        @else
            <p class="text-center"><span class="badge bg-success">Pembayaran Berhasil</span></p>
        @endif

        <hr>

        <h4 class="fw-bold text-center">Kode Bayar: <br> <span class="text-primary"> {{ $order->order_code }} </span> </h4>

        <hr>

        <h5 class="mb-3 text-center" >Detail Pesanan</h5>
        <table class="table table-borderless">
            @foreach ($orderItems as $orderItem)
                <tr>
                    <td>{{ Str::limit($orderItem->item_name, 25) }} ({{ $orderItem->quantity }})</td>
                    <td class="text-end">{{ 'Rp' . number_format($orderItem->total_price, 0, ',', '.') }}</td>
                </tr>
                
            @endforeach
        </table>

        <table class="table table-borderless">
            <tr>
                <th>Subtotal</th>
                <td class="text-end">{{ 'Rp' . number_format($order->subtotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Pajak (10%)</th>
                <td class="text-end">{{ 'Rp' . number_format($order->tax, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Total</th>
                <td class="text-end fw-bold">{{ 'Rp' . number_format($order->grandTotal, 0, ',', '.') }}</td>
            </tr>
        </table>

        @if ($order->payment_method == 'tunai')
            <p class="text-center mt-4">Tunjukkan kode bayar ini kepada kasir untuk menyelesaikan pembayaran. Jangan lupa senyum ya!</p>
        @elseif ($order->payment_method == 'qris')
            <p class="text-center mt-4">Yeay! Pembayaran sukses. Duduk manis ya , pesanan kamu segera kami proses!!</p>
        @endif

        <hr>

        <a href="{{ route('menu') }}" class="btn btn-primary d-block mx-auto">Kembali ke Menu</a>
    </div>
</div>

@endsection