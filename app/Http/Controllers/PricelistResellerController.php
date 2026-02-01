<?php

namespace App\Http\Controllers;

use App\Models\PricelistReseller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PricelistResellerController extends Controller
{
    public function index()
    {
        $pricelistResellers = PricelistReseller::orderBy('created_at', 'desc')->get();

        return view('pricelist-resellers.index', compact('pricelistResellers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        try {
            $thumbnailPath = $request->file('image')->store('pricelist_resellers', 'public');
            PricelistReseller::create([
                'path' => $thumbnailPath
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['image' => 'Failed to upload image. Please try again.'])->withInput();
        }

        return redirect()->route('pricelist-resellers.index')->with('success', 'Pricelist Reseller added successfully.');
    }

    public function update(Request $request, $id)
    {
        $pricelistReseller = PricelistReseller::findOrFail($id);

        $request->validate([
            'image' => 'nullable|image|max:2048',
        ]);

        try {
            if ($request->hasFile('image')) {
                if ($pricelistReseller->path) {
                    Storage::disk('public')->delete($pricelistReseller->path);
                }
                $thumbnailPath = $request->file('image')->store('pricelist_resellers', 'public');
                $pricelistReseller->path = $thumbnailPath;
            }

            $pricelistReseller->save();
        } catch (\Exception $e) {
            return back()->withErrors(['image' => 'Failed to upload image. Please try again.'])->withInput();
        }

        return redirect()->route('pricelist-resellers.index')->with('success', 'Pricelist Reseller updated successfully.');
    }

    public function destroy($id)
    {
        $pricelistReseller = PricelistReseller::findOrFail($id);

        if ($pricelistReseller->path) {
            Storage::disk('public')->delete($pricelistReseller->path);
        }

        $pricelistReseller->delete();

        return redirect()->route('pricelist-resellers.index')->with('success', 'Pricelist Reseller deleted successfully.');
    }

    public function toggleActive(PricelistReseller $pricelistReseller)
    {
        $pricelistReseller->active = !$pricelistReseller->active;
        $pricelistReseller->save();

        return redirect()->route('pricelist-resellers.index')->with('success', 'Pricelist Reseller status updated successfully.');
    }
}
