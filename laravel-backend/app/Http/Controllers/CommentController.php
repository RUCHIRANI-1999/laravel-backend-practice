<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // List all comments
    public function index()
    {
        return Comment::with('user', 'post')->get();
    }

    // Store a new comment
    public function store(Request $request)
    {
        $data = $request->validate([
            'post_id' => 'required|exists:posts,id',
            'body' => 'required|string',
        ]);

        $data['user_id'] = $request->user()->id;

        $comment = Comment::create($data);
        return response()->json($comment, 201);
    }

    // Show a single comment
    public function show(Comment $comment)
    {
        return $comment->load('user', 'post');
    }

    // Update a comment
    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment); // Optional if using policies

        $data = $request->validate([
            'body' => 'required|string',
        ]);

        $comment->update($data);
        return response()->json($comment);
    }

    // Delete a comment
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment); // Optional
        $comment->delete();
        return response()->json(['message' => 'Comment deleted']);
    }
}
