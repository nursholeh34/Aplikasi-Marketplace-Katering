@extends('layouts.app')
@section('title', 'Daftar Katering')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card"><div class="card-body p-4">
            <h2 class="h4">Daftar sebagai Katering</h2>
            <p class="text-muted">Buat akun merchant untuk mulai menjual menu ke kantor-kantor.</p>
            <form method="POST" action="{{ route('register.merchant.store') }}" class="row g-3">
                @csrf
                @include('partials.account-fields')
                <div class="col-12"><hr class="my-1"></div>
                @include('partials.merchant-fields', ['merchant' => null])
                <div class="col-12 d-flex gap-2 justify-content-end">
                    <a href="{{ route('home') }}" class="btn btn-light">Batal</a>
                    <button class="btn btn-brand" type="submit">Daftar</button>
                </div>
            </form>
        </div></div>
    </div>
</div>
@endsection
