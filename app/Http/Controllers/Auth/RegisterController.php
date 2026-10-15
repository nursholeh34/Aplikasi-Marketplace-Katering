<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function showMerchant(): View
    {
        return view('auth.register-merchant', ['cuisines' => Merchant::CUISINES]);
    }

    public function showCustomer(): View
    {
        return view('auth.register-customer');
    }

    public function storeMerchant(Request $request): RedirectResponse
    {
        $data = $request->validate($this->accountRules() + [
            'company_name' => ['required', 'string', 'max:150'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'],
            'cuisine_type' => ['required', Rule::in(Merchant::CUISINES)],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        return $this->register($request, $data, User::ROLE_MERCHANT);
    }

    public function storeCustomer(Request $request): RedirectResponse
    {
        $data = $request->validate($this->accountRules() + [
            'company_name' => ['required', 'string', 'max:150'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'],
        ]);

        return $this->register($request, $data, User::ROLE_CUSTOMER);
    }

    private function accountRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email:rfc', 'max:150', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ];
    }

    /** Buat akun + profil dalam satu transaksi agar tidak ada data setengah jadi. */
    private function register(Request $request, array $data, string $role): RedirectResponse
    {
        try {
            $user = DB::transaction(function () use ($data, $role) {
                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => $data['password'], // di-hash otomatis oleh cast 'hashed'
                    'role' => $role,
                ]);

                $profile = collect($data)->except(['name', 'email', 'password', 'password_confirmation'])->all();

                $role === User::ROLE_MERCHANT
                    ? $user->merchant()->create($profile)
                    : $user->customer()->create($profile);

                return $user;
            });
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput($request->except('password', 'password_confirmation'))
                ->with('error', 'Pendaftaran gagal diproses. Silakan coba lagi.');
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route($user->homeRoute())->with('success', 'Pendaftaran berhasil. Selamat datang!');
    }
}
