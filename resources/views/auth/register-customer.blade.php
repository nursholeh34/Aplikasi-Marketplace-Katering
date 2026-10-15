@extends('layouts.app')
@section('title', 'Daftar Kantor')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card"><div class="card-body p-4">
            <h2 class="h4">Daftar sebagai Kantor</h2>
            <p class="text-muted">Buat akun untuk memesan makan siang karyawan dari katering pilihan.</p>
            <form method="POST" action="{{ route('register.customer.store') }}" class="row g-3">
                @csrf
                @include('partials.account-fields')
                <div class="col-12"><hr class="my-1"></div>
                <div class="col-md-6">
                    <label class="form-label" for="company_name">Nama kantor / perusahaan</label>
                    <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}" class="form-control @error('company_name') is-invalid @enderror" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="phone">Telepon</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label" for="address">Alamat kantor</label>
                    <input type="text" id="address" name="address" value="{{ old('address') }}" class="form-control @error('address') is-invalid @enderror" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="city">Kota</label>
                    <input type="text" id="city" name="city" value="{{ old('city') }}" class="form-control @error('city') is-invalid @enderror" required>
                </div>
                <div class="col-12 d-flex gap-2 justify-content-end">
                    <a href="{{ route('home') }}" class="btn btn-light">Batal</a>
                    <button class="btn btn-brand" type="submit">Daftar</button>
                </div>
            </form>
        </div></div>
    </div>
</div>
@endsection
