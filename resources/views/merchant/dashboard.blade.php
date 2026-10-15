@extends('layouts.app')
@section('title', 'Dashboard Merchant')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <div>
        <h1 class="h3 mb-0">{{ $merchant->company_name }}</h1>
        <div class="text-muted">{{ $merchant->cuisine_type }} · {{ $merchant->city }}</div>
    </div>
    <a href="{{ route('merchant.menus.create') }}" class="btn btn-brand"><i class="bi bi-plus-lg me-1"></i>Tambah menu</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3"><div class="card"><div class="card-body"><div class="text-muted small">Total pesanan</div><div class="stat">{{ $stats['orders'] }}</div></div></div></div>
    <div class="col-6 col-lg-3"><div class="card"><div class="card-body"><div class="text-muted small">Menunggu konfirmasi</div><div class="stat">{{ $stats['pending'] }}</div></div></div></div>
    <div class="col-6 col-lg-3"><div class="card"><div class="card-body"><div class="text-muted small">Pendapatan (tidak termasuk batal)</div><div class="stat">@rp($stats['revenue'])</div></div></div></div>
    <div class="col-6 col-lg-3"><div class="card"><div class="card-body"><div class="text-muted small">Jumlah menu</div><div class="stat">{{ $stats['menus'] }}</div></div></div></div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h2 class="h5 mb-0">Pesanan terbaru</h2>
            <a href="{{ route('merchant.orders.index') }}" class="small">Lihat semua</a>
        </div>
        @include('merchant.orders._table', ['orders' => $recent, 'actions' => false])
    </div>
</div>
@endsection
