<?php

namespace App\Http\Controllers;

use App\Models\HardsellingFooter;
use Illuminate\Http\Request;

class HardsellingFooterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hardsellingFooter = HardsellingFooter::first();
        return view('hardsellings.footer.index', compact('hardsellingFooter'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('hardsellings.footer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'footer_text' => 'required',
            'background' => 'required',
            'youtube' => 'required',
            'instagram' => 'required',
            'tiktok' => 'required',
            'facebook' => 'required',
        ]);

        try {
            HardsellingFooter::create([
                'footer_text' => $request->footer_text,
                'background_color' => $request->background,
                'youtube' => $request->youtube,
                'instagram' => $request->instagram,
                'tiktok' => $request->tiktok,
                'facebook' => $request->facebook,
            ]);

            return redirect()->route('hardselling-footers.index')->with('success', 'Hardselling Footer created successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while creating the Hardselling Footer: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(HardsellingFooter $hardsellingFooter)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HardsellingFooter $hardsellingFooter)
    {
        return view('hardsellings.footer.edit', compact('hardsellingFooter'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HardsellingFooter $hardsellingFooter)
    {
        $request->validate([
            'footer_text' => 'required',
            'background' => 'required',
            'youtube' => 'required',
            'instagram' => 'required',
            'tiktok' => 'required',
            'facebook' => 'required',
        ]);

        try {
            $hardsellingFooter->update([
                'footer_text' => $request->footer_text,
                'background_color' => $request->background,
                'youtube' => $request->youtube,
                'instagram' => $request->instagram,
                'tiktok' => $request->tiktok,
                'facebook' => $request->facebook,
            ]);

            return redirect()->route('hardselling-footers.index')->with('success', 'Hardselling Footer updated successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while updating the Hardselling Footer: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HardsellingFooter $hardsellingFooter)
    {
        $hardsellingFooter->delete();
        return redirect()->route('hardselling-footers.index')->with('success', 'Hardselling Footer deleted successfully.');
    }
}
