@extends('layouts.app')
@section('title', 'Masuk')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card"><div class="card-body p-4">
            <h2 class="h4 mb-3">Masuk</h2>
            <form method="POST" action="{{ route('login.store') }}" class="vstack gap-3">
                @csrf
                <div>
                    <label class="form-label" for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus autocomplete="email">
                </div>
                <div>
                    <label class="form-label" for="password">Kata sandi</label>
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password">
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Ingat saya</label>
                </div>
                <button class="btn btn-brand" type="submit">Masuk</button>
            </form>
            <hr>
            <div class="small text-center">Belum punya akun?
                <a href="{{ route('register.customer') }}">Daftar sebagai kantor</a> atau
                <a href="{{ route('register.merchant') }}">sebagai katering</a>.
            </div>
        </div></div>
    </div>
</div>
@endsection
