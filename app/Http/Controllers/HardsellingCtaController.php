<?php

namespace App\Http\Controllers;

use App\Models\HardsellingCta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HardsellingCtaController extends Controller
{
    public function indexCta()
    {
        $cta = HardsellingCta::first(); // atau ->latest()->first()

        return view('hardsellings.cta.index', compact('cta'));
    }


    public function createCta()
    {
        return view('hardsellings.cta.create');
    }

    public function storeCta(Request $request)
    {
        $request->validate([
            'header_cta' => 'required|image|mimes:png,jpg,jpeg,webp|max:2048',
            'background' => 'required|string',

            'button_whatsapp_image' => 'required|image|max:2048',
            'button_whatsapp_link'  => 'required|string',

            'button_shopee_image' => 'required|image|max:2048',
            'button_shopee_link'  => 'required|string',

            'button_tiktok_image' => 'required|image|max:2048',
            'button_tiktok_link'  => 'required|string',

            'button_tokped_image' => 'required|image|max:2048',
            'button_tokped_link'  => 'required|string',

            'button_seller_image' => 'required|image|max:2048',
            'button_seller_link'  => 'required|string',
        ]);

        // helper upload biar clean
        $upload = function ($file, $folder) {
            return $file->store("hardselling/{$folder}", 'public');
        };

        HardsellingCta::create([
            'header' => $upload($request->file('header_cta'), 'header'),
            'background' => $request->background,

            'whatsapp' => $upload($request->file('button_whatsapp_image'), 'whatsapp'),
            'link_whatsapp' => $request->button_whatsapp_link,

            'shopee' => $upload($request->file('button_shopee_image'), 'shopee'),
            'link_shopee' => $request->button_shopee_link,

            'tiktok' => $upload($request->file('button_tiktok_image'), 'tiktok'),
            'link_tiktok' => $request->button_tiktok_link,

            'tokopedia' => $upload($request->file('button_tokped_image'), 'tokopedia'),
            'link_tokopedia' => $request->button_tokped_link,

            'seller' => $upload($request->file('button_seller_image'), 'seller'),
            'link_seller' => $request->button_seller_link,
        ]);

        return redirect()
            ->route('hardsellings.cta.index')
            ->with('success', 'Hardselling CTA berhasil disimpan');
    }

    public function editCta($id)
    {
        $cta = HardsellingCta::findOrFail($id);

        return view('hardsellings.cta.edit', compact('cta'));
    }

    public function updateCta(Request $request, $id)
    {
        $cta = HardsellingCta::findOrFail($id);

        // ✅ Validasi
        $request->validate([
            'header_cta'   => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'background'   => 'required|string',

            'whatsapp'     => 'nullable|image|max:2048',
            'link_whatsapp' => 'nullable|url',

            'shopee'       => 'nullable|image|max:2048',
            'link_shopee'  => 'nullable|url',

            'tiktok'       => 'nullable|image|max:2048',
            'link_tiktok'  => 'nullable|url',

            'tokpedia'     => 'nullable|image|max:2048',
            'link_tokpedia' => 'nullable|url',

            'seller'       => 'nullable|image|max:2048',
            'link_seller'  => 'nullable|url',
        ]);

        // ✅ Background
        $cta->background = $request->background;

        // ✅ Header CTA
        if ($request->hasFile('header_cta')) {
            // hapus lama
            if ($cta->header && Storage::disk('public')->exists($cta->header)) {
                Storage::disk('public')->delete($cta->header);
            }

            $cta->header = $request->file('header_cta')
                ->store('hardselling/header', 'public');
        }

        // ✅ Mapping button fields
        $buttons = [
            'whatsapp' => 'link_whatsapp',
            'shopee'   => 'link_shopee',
            'tiktok'   => 'link_tiktok',
            'tokopedia' => 'link_tokopedia',
            'seller'   => 'link_seller',
        ];

        foreach ($buttons as $image => $link) {

            // update link (boleh kosong)
            $cta->$link = $request->$link;

            // update image jika upload baru
            if ($request->hasFile($image)) {

                // hapus image lama
                if ($cta->$image && Storage::disk('public')->exists($cta->$image)) {
                    Storage::disk('public')->delete($cta->$image);
                }

                $cta->$image = $request->file($image)
                    ->store("hardselling/buttons/{$image}", 'public');
            }
        }

        // ✅ Simpan perubahan
        $cta->save();

        return redirect()
            ->route('hardsellings.cta.index')
            ->with('success', 'Hardselling CTA berhasil diperbarui');
    }

    public function destroyCta(Request $request, $id)
    {
        $cta = HardsellingCta::findOrFail($id);

        // ✅ Hapus header image
        if ($cta->header && Storage::disk('public')->exists($cta->header)) {
            Storage::disk('public')->delete($cta->header);
        }

        // ✅ List image buttons
        $images = [
            'whatsapp',
            'shopee',
            'tiktok',
            'tokpedia',
            'seller',
        ];

        // ✅ Hapus semua image button
        foreach ($images as $image) {
            if ($cta->$image && Storage::disk('public')->exists($cta->$image)) {
                Storage::disk('public')->delete($cta->$image);
            }
        }

        // ✅ Hapus data CTA
        $cta->delete();

        return redirect()
            ->route('hardsellings.cta.index')
            ->with('success', 'Hardselling CTA berhasil dihapus');
    }
}
