<?php

namespace App\Http\Controllers;

use App\Models\VideoPlayer;
use Illuminate\Http\Request;

class VideoPlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $videoplayer = VideoPlayer::first();
        return view('videoplayers.index', compact('videoplayer'));
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
        try {
            $validated = $request->validate([
                'url' => 'required|url',
            ]);

            VideoPlayer::create($validated);

            return redirect()->back()->with('success', 'Video Player added successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while adding the Video Player: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(VideoPlayer $videoPlayer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VideoPlayer $videoPlayer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VideoPlayer $videoPlayer)
    {
        try {
            $validated = $request->validate([
                'url' => 'required|url',
            ]);

            $videoPlayer->update($validated);

            return redirect()->back()->with('success', 'Video Player updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating the Video Player: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VideoPlayer $videoPlayer)
    {
        //
    }
}
