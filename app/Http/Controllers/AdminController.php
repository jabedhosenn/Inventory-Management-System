<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class AdminController extends Controller
{
    public function addCategory()
    {
        return view('admin.addcategory');
    }

    public function createCategory(Request $request)
    {
        $category = new Category();
        $category->category_name = $request->name;
        $category->save();

        return redirect()->route('admin.addcategory')->with('success', 'Category added successfully.');
    }

    public function viewCategory()
    {
        $categories = Category::all();
        return view('admin.viewcategory', compact('categories'));
    }
}
