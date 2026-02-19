<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'site_name' => Setting::get('site_name', 'Cosmetiqu'),
            'site_description' => Setting::get('site_description', 'Toko Kosmetik Online Terpercaya'),
            'site_logo' => Setting::get('site_logo'),
            'site_favicon' => Setting::get('site_favicon'),
            'about_title' => Setting::get('about_title', 'Tentang Kami'),
            'about_description' => Setting::get('about_description'),
            'contact_email' => Setting::get('contact_email'),
            'contact_phone' => Setting::get('contact_phone'),
            'contact_address' => Setting::get('contact_address'),
            'facebook' => Setting::get('facebook'),
            'instagram' => Setting::get('instagram'),
            'twitter' => Setting::get('twitter'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'site_favicon' => 'nullable|image|mimes:png,ico|max:1024',
            'about_description' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
        ]);

        // Handle logo upload
        if ($request->hasFile('site_logo')) {
            $oldLogo = Setting::get('site_logo');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }
            $logoPath = $request->file('site_logo')->store('settings', 'public');
            Setting::set('site_logo', $logoPath, 'image');
        }

        // Handle favicon upload
        if ($request->hasFile('site_favicon')) {
            $oldFavicon = Setting::get('site_favicon');
            if ($oldFavicon) {
                Storage::disk('public')->delete($oldFavicon);
            }
            $faviconPath = $request->file('site_favicon')->store('settings', 'public');
            Setting::set('site_favicon', $faviconPath, 'image');
        }

        // Save text settings
        $textSettings = [
            'site_name', 'site_description', 'about_title', 'about_description',
            'contact_email', 'contact_phone', 'contact_address',
            'facebook', 'instagram', 'twitter'
        ];

        foreach ($textSettings as $key) {
            if ($request->has($key)) {
                $type = in_array($key, ['about_description', 'contact_address']) ? 'textarea' : 'text';
                Setting::set($key, $request->input($key), $type);
            }
        }

        return redirect()->route('admin.settings.index')->with('success', 'Settings berhasil diupdate!');
    }
}
