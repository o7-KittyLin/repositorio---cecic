<?php

namespace App\Http\Controllers;

use App\Models\PaymentSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentSettingController extends Controller
{
    public function edit()
    {
        $setting = PaymentSetting::latest()->first();
        return view('payments.settings', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = PaymentSetting::latest()->first() ?? new PaymentSetting();

        $validated = $request->validate([
            'account_number' => ['required','regex:/^[0-9]{10,16}$/'],
            'key' => ['nullable','string','max:255'],
            'qr' => ['nullable','image','max:2048'],
        ], [
            'account_number.required' => 'El numero de cuenta es obligatorio.',
            'account_number.regex' => 'El numero de cuenta debe tener solo numeros (10 a 16 digitos).',
            'qr.image' => 'El archivo debe ser una imagen.',
            'qr.max' => 'La imagen no puede superar 2MB.',
        ]);

        if ($request->hasFile('qr')) {
            if ($setting->qr_path) {
                Storage::disk('public')->delete($setting->qr_path);
            }
            $validated['qr_path'] = $request->file('qr')->store('payments', 'public');
        }

        $validated['created_by'] = auth()->id();

        $setting->fill($validated);
        $setting->save();

        return back()->with('success', 'Configuracion de pago guardada.');
    }
}
