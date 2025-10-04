<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserInterestController extends Controller
{
    public function setInterest(Request $request)
    {
        $request->validate([
            'categories' => 'required | array | min:3 | max:12',
            'categories.*'=> 'exists:categories,id',
        ]);
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $interests = $user->interests()->sync($request->categories);

        return response()->json([
            'message' => 'Added Interested',
            'interests' => $interests,
        ],200);
    }

    public function showInterests()
    {   
        $user = Auth::user();
        $interests = $user->interests;

        return response()->json([
            'interests' => $interests,
        ]);
    }
}
