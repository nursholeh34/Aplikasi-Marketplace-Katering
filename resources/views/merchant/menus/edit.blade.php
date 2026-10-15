@extends('layouts.app')
@section('title', 'Edit Menu')

@section('content')
<div class="row justify-content-center"><div class="col-lg-7">
    <div class="card"><div class="card-body p-4">
        <h1 class="h4 mb-3">Edit menu</h1>
        <form method="POST" action="{{ route('merchant.menus.update', $menu) }}" enctype="multipart/form-data" class="row g-3">
            @csrf
            @method('PUT')
            @include('merchant.menus._form', ['menu' => $menu])
            <div class="col-12 d-flex gap-2 justify-content-end">
                <a href="{{ route('merchant.menus.index') }}" class="btn btn-light">Batal</a>
                <button class="btn btn-brand" type="submit">Simpan perubahan</button>
            </div>
        </form>
    </div></div>
</div></div>
@endsection
