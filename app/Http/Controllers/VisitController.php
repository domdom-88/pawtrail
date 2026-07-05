<?php

namespace App\Http\Controllers;

use App\Models\Spot;
use App\Models\Visit;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request, Spot $spot)
    {
        $validated = $request->validate([
            'dog_id' => 'required|exists:dogs,id',
            'notes' => 'nullable|string',
            'photos.*' => 'nullable|image|max:5120',
        ]);

        $dog = auth()->user()->dogs()->findOrFail($validated['dog_id']);

        $visit = Visit::create([
            'spot_id' => $spot->id,
            'dog_id' => $dog->id,
            'user_id' => auth()->id(),
            'notes' => $validated['notes'] ?? null,
            'visited_at' => now(),
        ]);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('visit-photos', 'public');

                $visit->images()->create(['path' => $path]);
            }
        }

        return back()->with('success', 'Visit logged!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Visit $visit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Visit $visit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Visit $visit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Visit $visit)
    {
        //
    }
}