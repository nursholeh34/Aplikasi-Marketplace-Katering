@extends('layouts.app')
@section('title', 'Invoice '.$order->invoice_number)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 no-print">
    <a href="{{ auth()->user()->isMerchant() ? route('merchant.orders.index') : route('customer.orders.index') }}" class="small text-decoration-none"><i class="bi bi-arrow-left"></i> Kembali</a>
    <button class="btn btn-outline-brand btn-sm" onclick="window.print()"><i class="bi bi-printer me-1"></i>Cetak / simpan PDF</button>
</div>

<div class="card"><div class="card-body p-4 p-md-5">
    <div class="d-flex flex-wrap justify-content-between gap-3 mb-4">
        <div>
            <div class="h3 mb-1">INVOICE</div>
            <div class="fw-semibold">{{ $order->invoice_number }}</div>
            <div class="text-muted small">Diterbitkan {{ $order->created_at->translatedFormat('d F Y, H:i') }}</div>
        </div>
        <div class="text-md-end">
            <span class="badge {{ $order->status_badge }} fs-6">{{ $order->status_label }}</span>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="text-muted small mb-1">Dari (Katering)</div>
            <div class="fw-semibold">{{ $order->merchant->company_name }}</div>
            <div class="small">{{ $order->merchant->address }}, {{ $order->merchant->city }}</div>
            <div class="small">{{ $order->merchant->phone }}</div>
        </div>
        <div class="col-md-4">
            <div class="text-muted small mb-1">Untuk (Kantor)</div>
            <div class="fw-semibold">{{ $order->customer->company_name }}</div>
            <div class="small">{{ $order->customer->address }}, {{ $order->customer->city }}</div>
            <div class="small">{{ $order->customer->phone }}</div>
        </div>
        <div class="col-md-4">
            <div class="text-muted small mb-1">Pengiriman</div>
            <div class="fw-semibold">{{ $order->delivery_date->translatedFormat('l, d F Y') }}</div>
            <div class="small">{{ $order->delivery_address }}</div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Menu</th><th class="text-end">Harga</th><th class="text-end">Porsi</th><th class="text-end">Subtotal</th></tr></thead>
            <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->menu_name }}</td>
                    <td class="text-end">@rp($item->price)</td>
                    <td class="text-end">{{ $item->quantity }}</td>
                    <td class="text-end">@rp($item->subtotal)</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
                <tr><th colspan="3" class="text-end">Total</th><th class="text-end fs-5">@rp($order->total)</th></tr>
            </tfoot>
        </table>
    </div>

    @if($order->notes)
        <div class="mt-3"><span class="text-muted small">Catatan:</span> {{ $order->notes }}</div>
    @endif
</div></div>
@endsection
