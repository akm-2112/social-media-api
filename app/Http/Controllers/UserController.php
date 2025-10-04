<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function view($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('view',$user);

        return response()->json($user);
    }

    public function update(Request $request, $id)
    {   
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        $request->validate([
            'name' => 'sometimes | string | max:255',
            'username' => 'sometimes | string | max:50 | unique:users,username,'.$user->id,
            'bio' => 'nullable | string | max:80',
            'profile_pic'=>'nullable | image | mimes:jpg,jpeg,png | max:2048',
            'password' => 'nullable | string | min:6 | confirmed',
        ]);
        
        if($request->hasFile('profile_pic')){
            $path = $request->files('profile_pic')->store('profile_pics','public');
            $data['profile_pic'] = $path;
        }
        if(!empty($data['password'])){
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return response()->json([
            'message' => 'Profile Updated',
            'user' => $user,
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('destroy',$user);
        $user->delete();

        return response()->json([
            'message' => 'Account Deleted Successfully',
        ],);
    }
}
