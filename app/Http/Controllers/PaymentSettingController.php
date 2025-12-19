<?php

namespace App\Http\Controllers;

use App\Models\PaymentSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentSettingController extends Controller
{
    public function edit()
    {
        $setting = PaymentSetting::first();
        return view('payment-settings.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'account_number' => [
                'required',
                'regex:/^[0-9]{10,16}$/',
            ],
            'payment_key' => 'nullable|string|max:255',
            'qr_image' => 'nullable|image|max:2048',
        ], [
            'account_number.required' => 'El número de cuenta es obligatorio.',
            'account_number.regex' => 'Debe tener solo números (10 a 16 dígitos).',
            'qr_image.image' => 'El archivo debe ser una imagen.',
            'qr_image.max' => 'La imagen no puede superar 2MB.',
        ]);

        $setting = PaymentSetting::first() ?? new PaymentSetting();

        if ($request->hasFile('qr_image')) {
            if ($setting->qr_image_path) {
                Storage::disk('public')->delete($setting->qr_image_path);
            }
            $setting->qr_image_path = $request->file('qr_image')->store('payment', 'public');
        }

        $setting->account_number = $request->account_number;
        $setting->payment_key = $request->payment_key;
        $setting->save();

        return back()->with('success', 'Configuración de pago actualizada.');
    }
}
