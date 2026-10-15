@extends('layouts.app')
@section('title', 'Profil Merchant')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card"><div class="card-body p-4">
            <h1 class="h4 mb-3">Profil Katering</h1>
            <form method="POST" action="{{ route('merchant.profile.update') }}" class="row g-3">
                @csrf
                @method('PUT')
                <div class="col-md-6">
                    <label class="form-label" for="name">Nama penanggung jawab</label>
                    <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email (tidak dapat diubah)</label>
                    <input type="email" value="{{ auth()->user()->email }}" class="form-control" disabled>
                </div>
                @include('partials.merchant-fields')
                <div class="col-12 text-end"><button class="btn btn-brand" type="submit">Simpan perubahan</button></div>
            </form>
        </div></div>
    </div>
</div>
@endsection
