@extends('layouts.app')
@section('title', 'Cari Katering')

@section('content')
<h1 class="h3 mb-3">Cari katering</h1>

<form method="GET" action="{{ route('customer.caterings.index') }}" class="card mb-4">
    <div class="card-body row g-2">
        <div class="col-lg-5">
            <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control" placeholder="Nama katering atau menu (mis. ayam bakar)" aria-label="Kata kunci">
        </div>
        <div class="col-6 col-lg-3">
            <select name="city" class="form-select" aria-label="Kota">
                <option value="">Semua kota</option>
                @foreach($cities as $city)
                    <option value="{{ $city }}" @selected(($filters['city'] ?? '') === $city)>{{ $city }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-lg-2">
            <select name="cuisine" class="form-select" aria-label="Jenis makanan">
                <option value="">Semua jenis</option>
                @foreach($cuisines as $c)
                    <option value="{{ $c }}" @selected(($filters['cuisine'] ?? '') === $c)>{{ $c }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-2 d-grid"><button class="btn btn-brand" type="submit"><i class="bi bi-search me-1"></i>Cari</button></div>
    </div>
</form>

@forelse($merchants as $merchant)
    @if($loop->first)<div class="row g-3">@endif
    <div class="col-md-6 col-lg-4">
        <div class="card h-100"><div class="card-body d-flex flex-column">
            <div class="d-flex justify-content-between align-items-start gap-2">
                <h2 class="h5 mb-1">{{ $merchant->company_name }}</h2>
                <span class="badge text-bg-light border">{{ $merchant->cuisine_type }}</span>
            </div>
            <div class="text-muted small mb-2"><i class="bi bi-geo-alt me-1"></i>{{ $merchant->city }}</div>
            <p class="small flex-grow-1">{{ \Illuminate\Support\Str::limit($merchant->description, 110) }}</p>
            <div class="d-flex justify-content-between align-items-center">
                <span class="small text-muted">mulai <span class="price">@rp($merchant->min_price)</span></span>
                <a href="{{ route('customer.caterings.show', $merchant) }}" class="btn btn-sm btn-brand">Lihat menu</a>
            </div>
        </div></div>
    </div>
    @if($loop->last)</div>@endif
@empty
    <div class="card"><div class="card-body text-center text-muted py-5">Tidak ada katering yang cocok dengan pencarian Anda.</div></div>
@endforelse

<div class="mt-4">{{ $merchants->links() }}</div>
@endsection
