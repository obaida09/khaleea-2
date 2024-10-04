<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\FrontEnd\CommentResource;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // Store a new comment for a post
    public function store(StoreCommentRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::user()->id;
        $comment = Comment::create($data);

        return new CommentResource($comment);
    }

    // Show all comments for a specific post
    // public function index()
    // {
    //     $post = Post::findOrFail($postId);
    //     $comments = $post->comments()->latest()->get();

    //     return CommentResource::collection($comments);
    // }

    // Delete a comment
    public function destroy(Comment $comment)
    {
        if ($comment->user_id != auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully'], 200);
    }
}
