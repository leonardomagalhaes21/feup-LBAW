<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\User;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all posts
        $posts = Post::latest()->get();

        // Return a view or JSON with the posts
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Return a view to create a new post
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'content' => 'required|string|max:1000',
            'date' => 'required|date|before_or_equal:today',
            'users_id' => 'required|exists:users,id',
        ]);

        // Create the post
        Post::create([
            'content' => $request->content,
            'date' => $request->date,
            'users_id' => $request->users_id,
        ]);

        // Redirect with a success message
        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        // Return a view to display the post details
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        // Return a view to edit the post
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        // Validate the request data
        $request->validate([
            'content' => 'required|string|max:1000',
            'date' => 'required|date|before_or_equal:today',
            'users_id' => 'required|exists:users,id',
        ]);

        // Update the post
        $post->update([
            'content' => $request->content,
            'date' => $request->date,
            'users_id' => $request->users_id,
        ]);

        // Redirect with a success message
        return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }

    public function like(Request $request, $postId)
    {
        $user = auth()->user(); 
        $post = Post::findOrFail($postId);

        if ($post->isLikedBy($user)) { 
            $post->likes()->where('users_id', $user->id)->delete(); 
        } else {
            $post->likes()->create(['users_id' => $user->id]); 
        }

        return response()->json([
            'success' => true,
            'likesCount' => $post->likesCount(), 
            'dislikesCount' => $post->dislikesCount(),
        ]);
    }

    public function dislike(Request $request, $postId)
    {
        $user = auth()->user();
        $post = Post::findOrFail($postId);

        if ($post->isDislikedBy($user)) {
            $post->dislikes()->where('users_id', $user->id)->delete();
        } else {
            $post->dislikes()->create(['users_id' => $user->id]); 
        }

        return response()->json([
            'success' => true,
            'likesCount' => $post->likesCount(),
            'dislikesCount' => $post->dislikesCount(), 
        ]);
    }
}
