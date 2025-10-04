<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{   
    public function index(Post $post)
    {
        $comment = $post->comments()->with(['user:id,name,username,profile_pic'])->latest()->paginate('20');
        return response()->json($comment);
    }

    public function store(Request $request, Post $post)
    {   
        $request->validate([
            'text' => 'required | string | max:300',
        ]);
        $comment = $post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->text,
        ]);

        $post->increment('comments_count');

        return response()->json([
            'message' => 'comment added',
            'comment' => $comment,
        ],201);
    }

    public function update(Request $request,Comment $comment)
    {   
        $this->authorize('update',$comment);
        $request->validate([
            'text' => 'required | string | max:300',
        ]);
        $comment->update(['content' => $request->text]);

        return response()->json([
            'message' => 'Comment updated',
            'content' =>$comment,
        ]);
    }

    public function destroy(Comment $comment){
        $this->authorize('delete',$comment);
        $comment->delete();

        $comment->post()->decrement('comments_count');
        return response()->json([
            'message' => 'comment deleted',
        ]);
    }
}
