@extends('layouts.app')
@section('title', 'Tambah Menu')

@section('content')
<div class="row justify-content-center"><div class="col-lg-7">
    <div class="card"><div class="card-body p-4">
        <h1 class="h4 mb-3">Tambah menu</h1>
        <form method="POST" action="{{ route('merchant.menus.store') }}" enctype="multipart/form-data" class="row g-3">
            @csrf
            @include('merchant.menus._form')
            <div class="col-12 d-flex gap-2 justify-content-end">
                <a href="{{ route('merchant.menus.index') }}" class="btn btn-light">Batal</a>
                <button class="btn btn-brand" type="submit">Simpan</button>
            </div>
        </form>
    </div></div>
</div></div>
@endsection
