<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Visit;
use Illuminate\Http\Request;

class CommentController extends Controller
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
    public function store(Request $request, Visit $visit)
    {
    $validated = $request->validate([
        'body' => 'required|string|max:1000',
    ]);

    Comment::create([
        'visit_id' => $visit->id,
        'user_id' => auth()->id(),
        'body' => $validated['body'],
    ]);

    return back()->with('success', 'Comment added!');
}

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== auth()->id()) {
            abort(403);
        }

        $comment->delete();

        return back()->with('success', 'Comment removed.');
    }
}
