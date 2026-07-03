<?php

namespace App\Http\Controllers;

use App\Models\Dog;
use Illuminate\Http\Request;

class DogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
{
    $dogs = auth()->user()->dogs;

    return view('dogs.index', compact('dogs'));
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
        'name' => 'required|string|max:255',
        'breed' => 'nullable|string|max:255',
        'age' => 'nullable|integer|min:0|max:30',
        'favourite_food' => 'nullable|string|max:255',
        'description' => 'nullable|string',
    ]);

    $validated['user_id'] = auth()->id();

    Dog::create($validated);

    return redirect()->route('dogs.index')->with('success', 'Dog added!');
}

    /**
     * Display the specified resource.
     */
    public function show(Dog $dog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dog $dog)
    {
    if ($dog->user_id !== auth()->id()) {
        abort(403);
    }

    return view('dogs.edit', compact('dog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dog $dog)
    {
        if ($dog->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'breed' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:0|max:30',
            'favourite_food' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $dog->update($validated);

        return redirect()->route('dogs.index')->with('success', 'Dog updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dog $dog)
    {
        if ($dog->user_id !== auth()->id()) {
            abort(403);
        }

        $dog->delete();

        return redirect()->route('dogs.index')->with('success', 'Dog removed.');
    }
}
