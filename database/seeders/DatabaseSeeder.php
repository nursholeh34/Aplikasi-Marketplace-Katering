<?php

namespace Database\Seeders;

use App\Models\Merchant;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Data contoh. Semua akun memakai kata sandi: password
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $sari = $this->merchant('Bu Sari', 'merchant1@example.com', [
            'company_name' => 'Dapur Bu Sari',
            'address' => 'Jl. Kebon Jeruk No. 12',
            'city' => 'Jakarta',
            'phone' => '081234567890',
            'cuisine_type' => 'Nusantara',
            'description' => 'Katering rumahan untuk makan siang kantor. Masakan Nusantara, porsi pas, dikirim tepat waktu.',
        ], [
            ['Nasi Box Ayam Bakar', 'Ayam bakar bumbu kecap, nasi, lalapan, sambal terasi, dan tempe goreng.', 28000],
            ['Nasi Box Rendang', 'Rendang daging sapi empuk, nasi putih, sayur singkong, dan sambal hijau.', 35000],
            ['Nasi Box Ikan Patin Pindang', 'Pindang patin segar, nasi, tumis kangkung, dan sambal.', 32000],
        ]);

        $pempek = $this->merchant('Pak Rizal', 'merchant2@example.com', [
            'company_name' => 'Pempek & Co',
            'address' => 'Jl. Jenderal Sudirman No. 88',
            'city' => 'Palembang',
            'phone' => '081298765432',
            'cuisine_type' => 'Palembang',
            'description' => 'Hidangan khas Palembang: pempek, tekwan, dan model, dengan cuko asli.',
        ], [
            ['Paket Pempek Campur', 'Lenjer, kapal selam, dan adaan dengan cuko khas Palembang (10 pcs).', 30000],
            ['Tekwan Spesial', 'Tekwan udang dengan kuah kaldu, bengkuang, dan soun.', 25000],
            ['Mi Celor', 'Mi kuah santan gurih dengan telur rebus dan udang.', 27000],
        ]);

        $customer = User::create([
            'name' => 'Dewi Lestari',
            'email' => 'customer@example.com',
            'password' => 'password',
            'role' => User::ROLE_CUSTOMER,
        ]);
        $office = $customer->customer()->create([
            'company_name' => 'PT Maju Bersama',
            'address' => 'Gedung Graha Lt. 5, Jl. HR Rasuna Said, Jakarta',
            'city' => 'Jakarta',
            'phone' => '0215550123',
        ]);

        // Contoh satu order beserta invoice.
        $order = Order::create([
            'customer_id' => $office->id,
            'merchant_id' => $sari->id,
            'delivery_date' => now()->addDays(2)->toDateString(),
            'delivery_address' => $office->address,
            'notes' => 'Kirim sebelum jam 11.30.',
            'status' => 'pending',
            'total' => 0,
        ]);

        $total = 0;
        foreach ([[0, 20], [1, 10]] as [$index, $qty]) {
            $menu = $sari->menus()->orderBy('id')->skip($index)->first();
            $subtotal = $menu->price * $qty;
            $total += $subtotal;
            $order->items()->create([
                'menu_id' => $menu->id,
                'menu_name' => $menu->name,
                'price' => $menu->price,
                'quantity' => $qty,
                'subtotal' => $subtotal,
            ]);
        }

        $order->update([
            'total' => $total,
            'invoice_number' => 'INV-'.now()->format('Ymd').'-'.str_pad((string) $order->id, 5, '0', STR_PAD_LEFT),
        ]);
    }

    private function merchant(string $name, string $email, array $profile, array $menus): Merchant
    {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => 'password',
            'role' => User::ROLE_MERCHANT,
        ]);

        $merchant = $user->merchant()->create($profile);

        foreach ($menus as [$menuName, $description, $price]) {
            $merchant->menus()->create([
                'name' => $menuName,
                'description' => $description,
                'price' => $price,
                'is_available' => true,
            ]);
        }

        return $merchant;
    }
}
