<?php

namespace App\Http\Controllers;

use App\Models\CategoryFaq;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $faqs = Faq::orderByRaw("CASE WHEN status = 'show' THEN 1 ELSE 2 END")
            ->orderBy('id', 'desc')
            ->get();

        $types = [
            ['id' => 1, 'target' => 'all'],
            ['id' => 2, 'target' => 'user'],
            ['id' => 3, 'target' => 'affiliate'],
            ['id' => 4, 'target' => 'reseller'],
        ];

        $categories = CategoryFaq::all();

        return view('faqs.index', compact('faqs', 'types', 'categories'));
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
        $request->validate([
            'question' => 'required',
            'answer' => 'required',
            'category_id' => 'required',
            'target' => 'required',
        ]);

        try {
            Faq::create([
                'question' => $request->question,
                'answer' => $request->answer,
                'category_id' => $request->category_id,
                'target' => $request->target,
            ]);

            return redirect()->route('faqs.index')->with('success', 'FAQ created successfully');
        } catch (\Exception $e) {
            return redirect()->route('faqs.index')->with('error', 'Failed to create FAQ');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Faq $faq)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Faq $faq)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Faq $faq)
    {
        $request->validate([
            'question' => 'required',
            'answer' => 'required',
            'category_id' => 'required',
            'target' => 'required',
        ]);

        try {
            $faq->update([
                'question' => $request->question,
                'answer' => $request->answer,
                'category_id' => $request->category_id,
                'target' => $request->target,
            ]);

            return redirect()->route('faqs.index')->with('success', 'FAQ updated successfully');
        } catch (\Exception $e) {
            return redirect()->route('faqs.index')->with('error', 'Failed to update FAQ');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faq $faq)
    {
        //
    }

    /**
     * Toggle the status of the specified resource.
     */
    public function toggleStatus(Faq $faq)
    {
        $faq->status = $faq->status === 'show' ? 'hide' : 'show';
        $faq->save();

        return redirect()->route('faqs.index')->with('success', 'Status updated successfully');
    }
}
