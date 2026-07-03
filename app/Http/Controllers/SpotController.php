<?php

namespace App\Http\Controllers;

use App\Models\Spot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SpotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $spots = Spot::latest()->get();

        return view('spots.index', compact('spots'));
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
            'place_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $response = Http::withHeaders([
            'User-Agent' => 'Pawtrail App (dominicbloordevelopment@gmail.com)',
        ])->get('https://nominatim.openstreetmap.org/search', [
            'q' => $validated['place_name'],
            'format' => 'json',
            'limit' => 1,
        ]);

        if (! $response->successful()) {
            return back()->withInput()->with('error', 'Could not reach the location service. Try again in a moment.');
        }

        $results = $response->json();

        if (empty($results)) {
            return back()->withInput()->with('error', "Couldn't find \"{$validated['place_name']}\" — try adding a town or country to be more specific.");
        }

        Spot::create([
            'created_by' => auth()->id(),
            'name' => $validated['place_name'],
            'latitude' => $results[0]['lat'],
            'longitude' => $results[0]['lon'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('spots.index')->with('success', 'Spot added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Spot $spot)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Spot $spot)
    {
        if ($spot->created_by !== auth()->id()) {
            abort(403);
        }

        return view('spots.edit', compact('spot'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Spot $spot)
    {
        if ($spot->created_by !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $spot->update($validated);

        return redirect()->route('spots.index')->with('success', 'Spot updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Spot $spot)
    {
        if ($spot->created_by !== auth()->id()) {
            abort(403);
        }

        $spot->delete();

        return redirect()->route('spots.index')->with('success', 'Spot removed.');
    }
}