<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class PostController extends Controller
{
    public function index()
    {   
        $posts = Post::with('users:id,name,username,profile_pic','categories:id,name')->paginate(30);
        return response()->json($posts);
    }

    public function show(Post $post)
    {   
        $post->load([
            'users:id,name,username,profile_pic',
            'categories:id,name',
            'likes.user:id,name,username,profile_pic',
            'comments.user:id,name,username,profile_pic',
        ]);
        return response()->json($post);
    }
    public function store(Request $request)
    {   
        
        $request->validate([
            'content' => 'required | string',
            'category_id' =>'nullable | exists:categories,id',
            'media' =>'nullable | file | mimes:jpg,jpeg,png,mp4,avi | max:20240',
        ]);
        $post = $request->only(['content','category_id']);
        $post['user_id'] = Auth::id();

        if($request->hasFile('media')){
            $post['media_url'] = $request->file('media')->store('post_media','public');
        }

        $data = Post::create($post);
        return response()->json([
            'message' => 'Post Created',
            'post' => $data,
        ],201);
    }

    public function update(Request $request, Post $post)
    {   
        $this->authorize('update', $post);
        $request->validate([
            'content' => 'required | string',
            'category_id' =>'nullable | exists:categories,id',
            
        ]);
        $post->update($request->only('content','category_id'));

        return response()->json([
            'message' => 'Edited Successfully',
            'post' =>$post,
        ]);
    }

    public function destroy(Post $post)
    {   
        $this->authorize('delete',$post);
        $post->delete();
        return response()->json([
            'message' => 'Deleted Successfully'
        ]);
    }

    public function myPosts(){
       
        $posts = Post::where('user_id',Auth::id())->latest()->paginate('30');
        return response()->json($posts, 200);
    }
}
