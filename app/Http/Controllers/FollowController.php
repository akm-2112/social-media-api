<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function follow(User $user)
    {   
       /** @var \App\Models\User $AuthUser */
        $AuthUser = Auth::user();

        if($AuthUser->id===$user->id)
        {
            return response()->json([
                'message'=>'You cannot follower yourself'
            ],400);
        }
        $AuthUser->following()->syncWithoutDetaching([$user->id]);
        return response()->json([
            'message' => 'You are following {$user->username}',
        ],201);
    }

    public function unfollow(User $user)
    {
        /** @var \App\Models\User $AuthUser */
        $AuthUser = Auth::user();
        $AuthUser->following()->detach([$user->id]);
        
        return response()->json([
            'message' => 'You unfollowed {$user->username}'
        ],200);
    }

    public function getFollowing(User $user)
    {   
       $following = $user->following()->select('id','name','username','profile_pic')->get();
        return response()->json($following);
    }

    public function getFollower(User $user)
    {   
        $followers = $user->followers()->select('id','name','username','profile_pic')->get();
        return response()->json($followers);
    }
}
