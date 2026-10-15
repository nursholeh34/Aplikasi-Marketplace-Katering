@php($actions = $actions ?? true)
<div class="table-responsive">
    <table class="table align-middle mb-0">
        <thead>
            <tr>
                <th>Invoice</th><th>Kantor</th><th>Tgl kirim</th><th class="text-end">Total</th><th>Status</th><th class="text-end">Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse($orders as $order)
            <tr>
                <td class="fw-semibold">{{ $order->invoice_number }}</td>
                <td>{{ $order->customer->company_name }}</td>
                <td>{{ $order->delivery_date->translatedFormat('d M Y') }}</td>
                <td class="text-end">@rp($order->total)</td>
                <td><span class="badge {{ $order->status_badge }}">{{ $order->status_label }}</span></td>
                <td class="text-end">
                    <div class="d-flex gap-1 justify-content-end flex-wrap">
                        <a href="{{ route('invoices.show', $order) }}" class="btn btn-sm btn-outline-brand">Invoice</a>
                        @if($actions && $order->nextStatuses())
                            <form method="POST" action="{{ route('merchant.orders.status', $order) }}" class="d-flex gap-1">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="form-select form-select-sm" aria-label="Ubah status">
                                    @foreach($order->nextStatuses() as $next)
                                        <option value="{{ $next }}">{{ \App\Models\Order::STATUS_LABELS[$next] }}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-sm btn-brand" type="submit">Ubah</button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center text-muted py-4">Belum ada pesanan.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
