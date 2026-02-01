<?php

namespace App\Http\Controllers;

use App\Models\Hardselling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HardsellingController extends Controller
{
    public function index()
    {
        $hardsellings = Hardselling::all();
        return view('hardsellings.index', compact('hardsellings'));
    }

    public function create()
    {
        return view('hardsellings.create');
    }

    public function store(Request $request)
    {
        foreach ($request->content as $i => $contentFile) {

            $contentPath = $contentFile->store('hardselling/content', 'public');
            $buttonPath = $request->button[$i]->store('hardselling/button', 'public');

            Hardselling::create([
                'content_image' => $contentPath,
                'button_image'  => $buttonPath,
                'button_link'   => $request->button_link[$i],
                'position'      => $request->position[$i],
                'sort'          => $i + 1,
            ]);
        }

        return redirect()->route('hardsellings.index')->with('success', 'Saved');
    }

    public function editPreview()
    {
        $hardsellings = Hardselling::all();
        return view('hardsellings.edit', compact('hardsellings'));
    }

    public function update(Request $request)
    {
        DB::transaction(function () use ($request) {

            // ===============================
            // 1. ID SYNC
            // ===============================
            $submittedIds = collect($request->ids)
                ->filter()
                ->map(fn($id) => (int) $id)
                ->toArray();

            $existingItems = Hardselling::all();
            $existingIds   = $existingItems->pluck('id')->toArray();

            // ===============================
            // 2. DELETE (DB + STORAGE)
            // ===============================
            $idsToDelete = array_diff($existingIds, $submittedIds);

            if (!empty($idsToDelete)) {
                $itemsToDelete = $existingItems->whereIn('id', $idsToDelete);

                foreach ($itemsToDelete as $item) {
                    if ($item->content_image) {
                        Storage::disk('public')->delete($item->content_image);
                    }
                    if ($item->button_image) {
                        Storage::disk('public')->delete($item->button_image);
                    }
                }

                Hardselling::whereIn('id', $idsToDelete)->delete();
            }

            // ===============================
            // 3. UPDATE & CREATE
            // ===============================
            foreach ($request->ids as $i => $id) {

                $data = [
                    'button_link' => $request->button_link[$i] ?? null,
                    'position'    => $request->position[$i] ?? 'top',
                    'sort'        => $i + 1,
                ];

                if ($id) {
                    // -------- UPDATE --------
                    $item = $existingItems->firstWhere('id', (int) $id);

                    if (isset($request->content[$i])) {
                        if ($item && $item->content_image) {
                            Storage::disk('public')->delete($item->content_image);
                        }
                        $data['content_image'] =
                            $request->content[$i]->store('hardselling/content', 'public');
                    }

                    if (isset($request->button[$i])) {
                        if ($item && $item->button_image) {
                            Storage::disk('public')->delete($item->button_image);
                        }
                        $data['button_image'] =
                            $request->button[$i]->store('hardselling/button', 'public');
                    }

                    Hardselling::where('id', $id)->update($data);
                } else {
                    // -------- CREATE --------
                    if (isset($request->content[$i])) {
                        $data['content_image'] =
                            $request->content[$i]->store('hardselling/content', 'public');
                    }

                    if (isset($request->button[$i])) {
                        $data['button_image'] =
                            $request->button[$i]->store('hardselling/button', 'public');
                    }

                    Hardselling::create($data);
                }
            }
        });

        return redirect()
            ->route('hardsellings.index')
            ->with('success', 'Hardselling updated successfully');
    }

    public function destroyAll()
    {
        DB::transaction(function () {

            $items = Hardselling::all();

            foreach ($items as $item) {
                if ($item->content_image) {
                    Storage::disk('public')->delete($item->content_image);
                }

                if ($item->button_image) {
                    Storage::disk('public')->delete($item->button_image);
                }
            }

            Hardselling::truncate(); // hapus semua data
        });

        return redirect()
            ->route('hardsellings.index')
            ->with('success', 'All hardsellings deleted successfully');
    }
}
