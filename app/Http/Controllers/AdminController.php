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

    public function editCategory($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.editcategory', compact('category'));
    }

    public function updateCategory(Request $request, $id)
    {
       $category = Category::findOrFail($id);
    $category->category_name = $request->name;
    $category->save();

    return redirect()->back()->with('success', 'Category updated successfully!');
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.viewcategory')->with('success', 'Category deleted successfully.');
    }
}
