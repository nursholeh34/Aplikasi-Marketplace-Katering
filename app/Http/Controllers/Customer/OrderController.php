<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = $request->user()->customer->orders()
            ->with('merchant')
            ->latest()
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    public function store(Request $request, Merchant $merchant): RedirectResponse
    {
        $data = $request->validate([
            'delivery_date' => ['required', 'date', 'after:today'],
            'delivery_address' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:500'],
            'qty' => ['required', 'array'],
            'qty.*' => ['nullable', 'integer', 'min:0', 'max:1000'],
        ]);

        // Ambil hanya menu dengan jumlah porsi > 0.
        $qty = collect($data['qty'])->filter(fn ($q) => (int) $q > 0);

        if ($qty->isEmpty()) {
            return back()->withInput()
                ->withErrors(['qty' => 'Pilih minimal satu menu dengan jumlah porsi lebih dari 0.']);
        }

        // Harga SELALU diambil dari database, bukan dari input pengguna.
        $menus = $merchant->menus()
            ->where('is_available', true)
            ->whereIn('id', $qty->keys()->all())
            ->get();

        if ($menus->count() !== $qty->count()) {
            return back()->withInput()
                ->withErrors(['qty' => 'Ada menu yang tidak tersedia. Muat ulang halaman lalu coba lagi.']);
        }

        try {
            $order = DB::transaction(function () use ($request, $merchant, $data, $menus, $qty) {
                $order = Order::create([
                    'customer_id' => $request->user()->customer->id,
                    'merchant_id' => $merchant->id,
                    'delivery_date' => $data['delivery_date'],
                    'delivery_address' => $data['delivery_address'],
                    'notes' => $data['notes'] ?? null,
                    'status' => 'pending',
                    'total' => 0,
                ]);

                $total = 0;
                foreach ($menus as $menu) {
                    $quantity = (int) $qty[$menu->id];
                    $subtotal = $menu->price * $quantity;
                    $total += $subtotal;

                    $order->items()->create([
                        'menu_id' => $menu->id,
                        'menu_name' => $menu->name,
                        'price' => $menu->price,
                        'quantity' => $quantity,
                        'subtotal' => $subtotal,
                    ]);
                }

                $order->update([
                    'total' => $total,
                    'invoice_number' => 'INV-'.now()->format('Ymd').'-'.str_pad((string) $order->id, 5, '0', STR_PAD_LEFT),
                ]);

                return $order;
            });
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Pesanan gagal diproses. Silakan coba lagi.');
        }

        return redirect()->route('invoices.show', $order)
            ->with('success', 'Pesanan berhasil dibuat. Invoice sudah diterbitkan.');
    }

    public function cancel(Request $request, Order $order): RedirectResponse
    {
        abort_unless($order->customer_id === $request->user()->customer->id, 403);

        if ($order->status !== 'pending') {
            return back()->with('error', 'Hanya pesanan berstatus "Menunggu konfirmasi" yang dapat dibatalkan.');
        }

        $order->update(['status' => 'cancelled']);

        return back()->with('success', "Pesanan {$order->invoice_number} dibatalkan.");
    }
}
