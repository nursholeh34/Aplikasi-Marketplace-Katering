<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    /** Invoice bisa dibuka oleh merchant maupun customer yang terlibat di order tsb. */
    public function show(Request $request, Order $order): View
    {
        $user = $request->user();

        $allowed = ($user->isMerchant() && $order->merchant_id === $user->merchant?->id)
            || ($user->isCustomer() && $order->customer_id === $user->customer?->id);

        abort_unless($allowed, 403);

        $order->load(['items', 'merchant', 'customer']);

        return view('invoice.show', compact('order'));
    }
}
