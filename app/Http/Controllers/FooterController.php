<?php

namespace App\Http\Controllers;

use App\Models\Footer;
use App\Models\FooterEtawa;
use App\Models\FooterInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FooterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $footer = Footer::with(['informations', 'etawas'])->first(); // cuma 1 footer
        return view('footers.index', compact('footer'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'logo_halal' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'logo_pom' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'whatsapp' => 'nullable|string|max:20',
            'link_facebook' => 'nullable|string|max:255',
            'link_instagram' => 'nullable|string|max:255',
            'link_youtube' => 'nullable|string|max:255',
            'link_tiktok' => 'nullable|string|max:255',
        ]);

        // Upload logo
        foreach (['logo', 'logo_halal', 'logo_pom'] as $field) {
            if ($request->hasFile($field)) {
                $validated[$field] = $request->file($field)->store('footers', 'public');
            }
        }

        // Simpan footer utama
        $footer = Footer::create($validated);

        // Simpan informasi
        if ($request->has('information')) {
            foreach ($request->information as $info) {
                if (!empty($info['name'])) {
                    FooterInformation::create([
                        'footer_id' => $footer->id,
                        'name' => $info['name'],
                        'link' => $info['link'] ?? null,
                    ]);
                }
            }
        }

        // Simpan etawa
        if ($request->has('etawa')) {
            foreach ($request->etawa as $etawa) {
                if (!empty($etawa['name'])) {
                    FooterEtawa::create([
                        'footer_id' => $footer->id,
                        'name' => $etawa['name'],
                        'link' => $etawa['link'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('footers.index')->with('success', 'Footer berhasil disimpan!');
    }
    /**
     * Display the specified resource.
     */
    public function show(Footer $footer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Footer $footer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $footer = Footer::findOrFail($id);

        $validated = $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'logo_halal' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'logo_pom' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'whatsapp' => 'nullable|string|max:20',
            'link_facebook' => 'nullable|string|max:255',
            'link_instagram' => 'nullable|string|max:255',
            'link_youtube' => 'nullable|string|max:255',
            'link_tiktok' => 'nullable|string|max:255',
        ]);

        // Upload logo baru jika ada
        foreach (['logo', 'logo_halal', 'logo_pom'] as $field) {
            if ($request->hasFile($field)) {
                // hapus lama
                if ($footer->$field && Storage::disk('public')->exists($footer->$field)) {
                    Storage::disk('public')->delete($footer->$field);
                }
                // upload baru
                $validated[$field] = $request->file($field)->store('footers', 'public');
            } else {
                $validated[$field] = $footer->$field;
            }
        }

        // Update footer utama
        $footer->update($validated);

        // 🔁 Sinkronisasi data informasi & etawa
        $footer->informations()->delete();
        $footer->etawas()->delete();

        if ($request->has('information')) {
            foreach ($request->information as $info) {
                if (!empty($info['name'])) {
                    FooterInformation::create([
                        'footer_id' => $footer->id,
                        'name' => $info['name'],
                        'link' => $info['link'] ?? null,
                    ]);
                }
            }
        }

        if ($request->has('etawa')) {
            foreach ($request->etawa as $etawa) {
                if (!empty($etawa['name'])) {
                    FooterEtawa::create([
                        'footer_id' => $footer->id,
                        'name' => $etawa['name'],
                        'link' => $etawa['link'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('footers.index')->with('success', 'Footer berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Footer $footer)
    {
        //
    }
}
