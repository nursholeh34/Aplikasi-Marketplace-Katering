@extends('layouts.app')
@section('title', $merchant->company_name)

@section('content')
<a href="{{ route('customer.caterings.index') }}" class="small text-decoration-none"><i class="bi bi-arrow-left"></i> Kembali ke pencarian</a>
<div class="d-flex flex-wrap justify-content-between align-items-start my-3 gap-2">
    <div>
        <h1 class="h3 mb-1">{{ $merchant->company_name }}</h1>
        <div class="text-muted small">{{ $merchant->cuisine_type }} · {{ $merchant->address }}, {{ $merchant->city }} · {{ $merchant->phone }}</div>
        @if($merchant->description)<p class="mt-2 mb-0">{{ $merchant->description }}</p>@endif
    </div>
</div>

<form method="POST" action="{{ route('customer.orders.store', $merchant) }}">
    @csrf
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="row g-3">
                @foreach($menus as $menu)
                    <div class="col-md-6">
                        <div class="card h-100">
                            @if($menu->photo_url)
                                <img src="{{ $menu->photo_url }}" class="menu-photo" alt="{{ $menu->name }}" loading="lazy">
                            @else
                                <div class="menu-photo menu-photo-empty"><i class="bi bi-image"></i></div>
                            @endif
                            <div class="card-body d-flex flex-column">
                                <h2 class="h6 mb-1">{{ $menu->name }}</h2>
                                <p class="small text-muted flex-grow-1">{{ $menu->description }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="price">@rp($menu->price)</span>
                                    <div class="input-group input-group-sm" style="width:130px">
                                        <input type="number" name="qty[{{ $menu->id }}]" min="0" max="1000"
                                               value="{{ old('qty.'.$menu->id, 0) }}" class="form-control text-end qty-input"
                                               data-price="{{ $menu->price }}" aria-label="Porsi {{ $menu->name }}">
                                        <span class="input-group-text">porsi</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card sticky-summary"><div class="card-body vstack gap-3">
                <h2 class="h5 mb-0">Detail pengiriman</h2>
                <div>
                    <label class="form-label" for="delivery_date">Tanggal pengiriman</label>
                    <input type="date" id="delivery_date" name="delivery_date" min="{{ now()->addDay()->toDateString() }}"
                           value="{{ old('delivery_date') }}" class="form-control @error('delivery_date') is-invalid @enderror" required>
                </div>
                <div>
                    <label class="form-label" for="delivery_address">Alamat pengiriman</label>
                    <textarea id="delivery_address" name="delivery_address" rows="2" class="form-control @error('delivery_address') is-invalid @enderror" required>{{ old('delivery_address', $customer->address) }}</textarea>
                </div>
                <div>
                    <label class="form-label" for="notes">Catatan (opsional)</label>
                    <textarea id="notes" name="notes" rows="2" class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                </div>
                <div class="d-flex justify-content-between border-top pt-3">
                    <span class="text-muted">Perkiraan total</span>
                    <span class="fw-bold fs-5" id="total">Rp 0</span>
                </div>
                <button class="btn btn-brand" type="submit">Pesan &amp; terbitkan invoice</button>
            </div></div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    const inputs = document.querySelectorAll('.qty-input');
    const totalEl = document.getElementById('total');
    const fmt = new Intl.NumberFormat('id-ID');
    function recalc() {
        let total = 0;
        inputs.forEach(i => total += (parseInt(i.value) || 0) * parseInt(i.dataset.price));
        totalEl.textContent = 'Rp ' + fmt.format(total);
    }
    inputs.forEach(i => i.addEventListener('input', recalc));
    recalc();
</script>
@endpush
