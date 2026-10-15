@extends('layouts.app')
@section('title', 'Beranda')

@section('content')
<section class="hero p-4 p-md-5 mb-4">
    <div class="row align-items-center g-4">
        <div class="col-lg-7">
            <h1 class="display-5 fw-bold mb-3">Makan siang satu kantor, dipesan dalam sekali klik.</h1>
            <p class="lead mb-4">Pilih katering yang cocok, tentukan menu, jumlah porsi, dan tanggal kirim. Invoice terbit otomatis dan bisa dibuka oleh kantor maupun katering.</p>
            @guest
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('register.customer') }}" class="btn btn-warning btn-lg fw-semibold">Saya dari kantor</a>
                    <a href="{{ route('register.merchant') }}" class="btn btn-outline-light btn-lg">Saya pemilik katering</a>
                </div>
            @else
                <a href="{{ route(auth()->user()->homeRoute()) }}" class="btn btn-warning btn-lg fw-semibold">Ke dashboard saya</a>
            @endguest
        </div>
        <div class="col-lg-5">
            <div class="row g-3 text-center">
                <div class="col-6"><div class="p-3 rounded-3" style="background:rgba(255,255,255,.08)"><i class="bi bi-search fs-2 brand-mark"></i><div class="mt-2 fw-semibold">Cari katering</div><div class="small text-white-50">Berdasarkan kota &amp; jenis makanan</div></div></div>
                <div class="col-6"><div class="p-3 rounded-3" style="background:rgba(255,255,255,.08)"><i class="bi bi-calendar-check fs-2 brand-mark"></i><div class="mt-2 fw-semibold">Atur jadwal</div><div class="small text-white-50">Pilih tanggal pengiriman</div></div></div>
                <div class="col-6"><div class="p-3 rounded-3" style="background:rgba(255,255,255,.08)"><i class="bi bi-receipt fs-2 brand-mark"></i><div class="mt-2 fw-semibold">Invoice otomatis</div><div class="small text-white-50">Dapat diakses kedua pihak</div></div></div>
                <div class="col-6"><div class="p-3 rounded-3" style="background:rgba(255,255,255,.08)"><i class="bi bi-shop fs-2 brand-mark"></i><div class="mt-2 fw-semibold">Kelola menu</div><div class="small text-white-50">Foto, deskripsi, harga</div></div></div>
            </div>
        </div>
    </div>
</section>
@endsection
