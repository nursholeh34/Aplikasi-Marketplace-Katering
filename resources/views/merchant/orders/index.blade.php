@extends('layouts.app')
@section('title', 'Daftar Pesanan')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <h1 class="h3 mb-0">Pesanan masuk</h1>
    <form method="GET" class="d-flex gap-2">
        <select name="status" class="form-select" onchange="this.form.submit()" aria-label="Filter status">
            <option value="">Semua status</option>
            @foreach(\App\Models\Order::STATUS_LABELS as $key => $label)
                <option value="{{ $key }}" @selected($status === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </form>
</div>

<div class="card"><div class="card-body">
    @include('merchant.orders._table', ['orders' => $orders, 'actions' => true])
</div></div>
<div class="mt-3">{{ $orders->links() }}</div>
@endsection
