@extends('layouts.app')
@section('title', 'Pesanan Saya')

@section('content')
<h1 class="h3 mb-3">Pesanan saya</h1>
<div class="card"><div class="card-body">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>Invoice</th><th>Katering</th><th>Tgl kirim</th><th class="text-end">Total</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
            @forelse($orders as $order)
                <tr>
                    <td class="fw-semibold">{{ $order->invoice_number }}</td>
                    <td>{{ $order->merchant->company_name }}</td>
                    <td>{{ $order->delivery_date->translatedFormat('d M Y') }}</td>
                    <td class="text-end">@rp($order->total)</td>
                    <td><span class="badge {{ $order->status_badge }}">{{ $order->status_label }}</span></td>
                    <td class="text-end">
                        <div class="d-flex gap-1 justify-content-end">
                            <a href="{{ route('invoices.show', $order) }}" class="btn btn-sm btn-outline-brand">Invoice</a>
                            @if($order->status === 'pending')
                                <form method="POST" action="{{ route('customer.orders.cancel', $order) }}" onsubmit="return confirm('Batalkan pesanan ini?')">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Batalkan</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada pesanan. <a href="{{ route('customer.caterings.index') }}">Cari katering</a></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div></div>
<div class="mt-3">{{ $orders->links() }}</div>
@endsection
