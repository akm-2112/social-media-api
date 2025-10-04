<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getAllCategories()
    {   
        $categories = Category::all();
        return response()->json($categories,200);
    }

    public function getPostByCategory(Category $category)
    {   
        $post = $category->posts()->with([
                    'users:id,name,username,profile_pic','likes','comments',])
                    ->latest()->paginate(30);
        return response()->json($post,200);
    }

    public function store(Request $request)
    {   
        $this->authorize('create', $request);
        
        $request->validate([
            'name' => 'required | string | max:255 | unique',
        ]);
        $category = Category::create($request->only('name'));

        return response()->json([
            'message' => 'Category Created',
            'category' => $category,
        ],201);
    }

    public function update(Request $request, Category $category)
    {   
        $this->authorize('update',$request);
        $request->validate([
            'name' => 'required | string | max:255 | unique',
        ]);
        $category->update($request->only('name'));

        return response()->json([
            'message' => 'Category Updated',
            'category' => $category,
        ],200);
    }
    
    public function destroy(Category $category, User $user)
    {   
        $this->authorize('delete', $category);
        $category->delete();
        return response()->json([
            'message' => 'Category Deleted',
        ],200);
    }
}
