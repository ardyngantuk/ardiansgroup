<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AdminSettingsController extends Controller
{
    public function index(Request $request): View
    {
        Setting::ensureDefaults();

        return view('admin.settings.index', [
            'settings' => Setting::query()->get()->keyBy('key'),
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'store_name' => ['required', 'string', 'max:150'],
            'store_tagline' => ['nullable', 'string', 'max:255'],
            'store_whatsapp' => ['required', 'string', 'regex:/^[0-9+\-\s()]{8,30}$/'],
            'store_email' => ['nullable', 'email', 'max:190'],
            'store_address' => ['nullable', 'string', 'max:1000'],
            'store_description' => ['nullable', 'string', 'max:500'],
            'store_hours' => ['nullable', 'string', 'max:100'],
            'store_logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'guest_checkout' => ['required', 'boolean'],
            'minimum_order' => ['required', 'numeric', 'min:0'],
            'cod_enabled' => ['required', 'boolean'],
            'manual_transfer_enabled' => ['required', 'boolean'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'account_number' => ['nullable', 'string', 'max:50'],
            'account_holder' => ['nullable', 'string', 'max:150'],
            'shipping_enabled' => ['required', 'boolean'],
            'default_shipping_cost' => ['required', 'numeric', 'min:0'],
            'free_shipping_minimum' => ['required', 'numeric', 'min:0'],
            'orders_notification' => ['required', 'boolean'],
            'payments_notification' => ['required', 'boolean'],
            'low_stock_notification' => ['required', 'boolean'],
        ]);

        if ($data['manual_transfer_enabled'] && blank($data['bank_name'])) {
            return back()->withErrors(['bank_name' => 'Nama bank wajib diisi jika transfer manual aktif.'])->withInput();
        }

        if ($data['manual_transfer_enabled'] && blank($data['account_number'])) {
            return back()->withErrors(['account_number' => 'Nomor rekening wajib diisi jika transfer manual aktif.'])->withInput();
        }

        if ($request->hasFile('store_logo')) {
            $oldLogo = Setting::getValue('store.logo');
            $path = $request->file('store_logo')->store('settings', 'public');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }
            Setting::setValue('store.logo', $path);
        }

        Setting::setMany([
            'store.name' => $data['store_name'],
            'store.tagline' => $data['store_tagline'] ?? '',
            'store.whatsapp' => $data['store_whatsapp'],
            'store.email' => $data['store_email'] ?? '',
            'store.address' => $data['store_address'] ?? '',
            'store.description' => $data['store_description'] ?? '',
            'store.hours' => $data['store_hours'] ?? '',
            'order.guest_checkout' => $data['guest_checkout'],
            'order.minimum_order' => $data['minimum_order'],
            'payment.cod_enabled' => $data['cod_enabled'],
            'payment.manual_transfer_enabled' => $data['manual_transfer_enabled'],
            'payment.bank_name' => $data['bank_name'] ?? '',
            'payment.account_number' => $data['account_number'] ?? '',
            'payment.account_holder' => $data['account_holder'] ?? '',
            'shipping.enabled' => $data['shipping_enabled'],
            'shipping.default_cost' => $data['default_shipping_cost'],
            'shipping.free_minimum' => $data['free_shipping_minimum'],
            'notification.orders' => $data['orders_notification'],
            'notification.payments' => $data['payments_notification'],
            'notification.low_stock' => $data['low_stock_notification'],
        ]);

        return back()->with('success', 'Pengaturan toko berhasil disimpan.');
    }

    public function updateAccount(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email,' . $request->user()->id],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);
        $request->user()->update($data);

        return back()->with('success', 'Profil admin berhasil diperbarui.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);
        $request->user()->update(['password' => Hash::make($data['password'])]);

        return back()->with('success', 'Password berhasil diubah.');
    }
}
