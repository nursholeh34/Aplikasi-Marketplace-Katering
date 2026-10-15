<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function dashboard(Request $request): View
    {
        $merchant = $request->user()->merchant;

        return view('merchant.dashboard', [
            'merchant' => $merchant,
            'stats' => [
                'orders' => $merchant->orders()->count(),
                'pending' => $merchant->orders()->where('status', 'pending')->count(),
                'revenue' => (int) $merchant->orders()->where('status', '!=', 'cancelled')->sum('total'),
                'menus' => $merchant->menus()->count(),
            ],
            'recent' => $merchant->orders()->with('customer')->latest()->limit(5)->get(),
        ]);
    }

    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();

        $orders = $request->user()->merchant->orders()
            ->with('customer')
            ->when(array_key_exists($status, Order::STATUS_LABELS), fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('merchant.orders.index', compact('orders', 'status'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        abort_unless($order->merchant_id === $request->user()->merchant->id, 403);

        $data = $request->validate([
            'status' => ['required', Rule::in($order->nextStatuses())],
        ]);

        try {
            $order->update(['status' => $data['status']]);
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Status order gagal diperbarui.');
        }

        return back()->with('success', "Status {$order->invoice_number} diubah menjadi \"{$order->status_label}\".");
    }
}
