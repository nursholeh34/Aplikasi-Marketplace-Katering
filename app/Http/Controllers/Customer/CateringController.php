<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CateringController extends Controller
{
    /** Pencarian katering: kata kunci, kota/lokasi, dan jenis makanan. */
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'cuisine' => ['nullable', 'string', 'max:50'],
        ]);

        $merchants = Merchant::query()
            ->whereHas('menus', fn ($m) => $m->where('is_available', true))
            ->withMin(['menus as min_price' => fn ($m) => $m->where('is_available', true)], 'price')
            ->when($filters['q'] ?? null, function ($query, $q) {
                $query->where(function ($w) use ($q) {
                    $w->where('company_name', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%")
                        ->orWhereHas('menus', fn ($m) => $m->where('name', 'like', "%{$q}%"));
                });
            })
            ->when($filters['city'] ?? null, fn ($query, $city) => $query->where('city', $city))
            ->when($filters['cuisine'] ?? null, fn ($query, $c) => $query->where('cuisine_type', $c))
            ->orderBy('company_name')
            ->paginate(9)
            ->withQueryString();

        return view('customer.caterings.index', [
            'merchants' => $merchants,
            'filters' => $filters,
            'cities' => Merchant::query()->distinct()->orderBy('city')->pluck('city'),
            'cuisines' => Merchant::CUISINES,
        ]);
    }

    public function show(Request $request, Merchant $merchant): View
    {
        return view('customer.caterings.show', [
            'merchant' => $merchant,
            'menus' => $merchant->menus()->where('is_available', true)->orderBy('name')->get(),
            'customer' => $request->user()->customer,
        ]);
    }
}
