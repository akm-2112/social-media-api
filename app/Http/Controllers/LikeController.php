<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function store(Post $post)
    {   
        if($post->likes()->where('user_id',Auth::id())->exists()){
            return response()->json([
                'message' => 'Already Liked'
            ],400);
        }
        $post->likes()->create([
        'user_id'=>Auth::id(),]);

        $post->increment('likes_count');
        return response()->json([
            'message' => 'Post Liked',
        ]);
    }

    public function destroy(Post $post)
    {   

        $like = $post->likes()->where('user_id',Auth::id())->first();

        if(!$like)
        {
            return response()->json([
                'message' => 'not liked yet',
            ],400);
        }
        $like->delete();

        $post->decrement('likes_count');
        return response()->json([
            'message' => 'Like removed',
        ]);
    }
}
