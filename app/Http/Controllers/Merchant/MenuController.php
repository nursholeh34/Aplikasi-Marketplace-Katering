<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(Request $request): View
    {
        $menus = $request->user()->merchant->menus()->latest()->paginate(9);

        return view('merchant.menus.index', compact('menus'));
    }

    public function create(): View
    {
        return view('merchant.menus.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules(photoRequired: true));

        try {
            $path = $request->file('photo')->store('menus', 'public');

            $request->user()->merchant->menus()->create([
                'name' => $data['name'],
                'description' => $data['description'],
                'price' => $data['price'],
                'photo' => $path,
                'is_available' => $request->boolean('is_available'),
            ]);
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Menu gagal disimpan. Silakan coba lagi.');
        }

        return redirect()->route('merchant.menus.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(Request $request, Menu $menu): View
    {
        $this->authorizeMenu($request, $menu);

        return view('merchant.menus.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu): RedirectResponse
    {
        $this->authorizeMenu($request, $menu);
        $data = $request->validate($this->rules(photoRequired: false));

        try {
            $attributes = [
                'name' => $data['name'],
                'description' => $data['description'],
                'price' => $data['price'],
                'is_available' => $request->boolean('is_available'),
            ];

            if ($request->hasFile('photo')) {
                $newPath = $request->file('photo')->store('menus', 'public');
                $oldPath = $menu->photo;
                $attributes['photo'] = $newPath;
            }

            $menu->update($attributes);

            if (isset($oldPath) && $oldPath) {
                Storage::disk('public')->delete($oldPath);
            }
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Menu gagal diperbarui. Silakan coba lagi.');
        }

        return redirect()->route('merchant.menus.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Request $request, Menu $menu): RedirectResponse
    {
        $this->authorizeMenu($request, $menu);

        try {
            $photo = $menu->photo;
            $menu->delete(); // order_items.menu_id jadi NULL, riwayat invoice tetap aman

            if ($photo) {
                Storage::disk('public')->delete($photo);
            }
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Menu gagal dihapus. Silakan coba lagi.');
        }

        return redirect()->route('merchant.menus.index')->with('success', 'Menu berhasil dihapus.');
    }

    private function rules(bool $photoRequired): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string', 'max:1000'],
            'price' => ['required', 'integer', 'min:1000', 'max:10000000'],
            'photo' => [$photoRequired ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_available' => ['nullable', 'boolean'],
        ];
    }

    /** Merchant hanya boleh mengubah menu miliknya sendiri. */
    private function authorizeMenu(Request $request, Menu $menu): void
    {
        abort_unless($menu->merchant_id === $request->user()->merchant->id, 403);
    }
}
