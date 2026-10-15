@extends('layouts.app')
@section('title', 'Kelola Menu')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Menu saya</h1>
    <a href="{{ route('merchant.menus.create') }}" class="btn btn-brand"><i class="bi bi-plus-lg me-1"></i>Tambah menu</a>
</div>

@forelse($menus as $menu)
    @if($loop->first)<div class="row g-3">@endif
    <div class="col-sm-6 col-lg-4">
        <div class="card h-100">
            @if($menu->photo_url)
                <img src="{{ $menu->photo_url }}" class="menu-photo" alt="{{ $menu->name }}" loading="lazy">
            @else
                <div class="menu-photo menu-photo-empty"><i class="bi bi-image"></i></div>
            @endif
            <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between gap-2">
                    <h2 class="h6 mb-1">{{ $menu->name }}</h2>
                    @unless($menu->is_available)<span class="badge text-bg-secondary align-self-start">Nonaktif</span>@endunless
                </div>
                <p class="text-muted small flex-grow-1">{{ \Illuminate\Support\Str::limit($menu->description, 100) }}</p>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="price">@rp($menu->price)</span>
                    <div class="d-flex gap-1">
                        <a href="{{ route('merchant.menus.edit', $menu) }}" class="btn btn-sm btn-outline-brand">Edit</a>
                        <form method="POST" action="{{ route('merchant.menus.destroy', $menu) }}" onsubmit="return confirm('Hapus menu ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if($loop->last)</div>@endif
@empty
    <div class="card"><div class="card-body text-center text-muted py-5">
        Belum ada menu. <a href="{{ route('merchant.menus.create') }}">Tambahkan menu pertama Anda.</a>
    </div></div>
@endforelse

<div class="mt-4">{{ $menus->links() }}</div>
@endsection
