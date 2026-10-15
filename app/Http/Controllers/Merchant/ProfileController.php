<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('merchant.profile', [
            'merchant' => $request->user()->merchant,
            'cuisines' => Merchant::CUISINES,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'company_name' => ['required', 'string', 'max:150'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'],
            'cuisine_type' => ['required', Rule::in(Merchant::CUISINES)],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        try {
            DB::transaction(function () use ($request, $data) {
                $request->user()->update(['name' => $data['name']]);
                $request->user()->merchant->update(collect($data)->except('name')->all());
            });
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Profil gagal disimpan. Silakan coba lagi.');
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
