<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // List all posts
    public function index()
    {
        return Post::with('user', 'comments')->get();
    }

    // Store a new post
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $data['user_id'] = $request->user()->id;

        $post = Post::create($data);
        return response()->json($post, 201);
    }

    // Show a single post
    public function show(Post $post)
    {
        return $post->load('user', 'comments');
    }

    // Update a post
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post); // Optional if using policies

        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
        ]);

        $post->update($data);
        return response()->json($post);
    }

    // Delete a post
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post); // Optional if using policies
        $post->delete();
        return response()->json(['message' => 'Post deleted']);
    }
}
