<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;

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

    public function addSupplier()
    {
        return view('admin.addsupplier');
    }

    public function createSupplier(Request $request)
    {
        $supplier = new Supplier();
        $supplier->supplier_name = $request->name;
        $supplier->phone = $request->phone;
        $supplier->email = $request->email;
        $supplier->address = $request->address;
        $supplier->save();

        return redirect()->route('admin.addsupplier')->with('success', 'Supplier added successfully.');
    }

    public function viewSupplier()
    {
        $suppliers = Supplier::all();
        return view('admin.viewsupplier', compact('suppliers'));
    }

    public function editSupplier($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('admin.editsupplier', compact('supplier'));
    }

    public function updateSupplier(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->supplier_name = $request->name;
        $supplier->phone = $request->phone;
        $supplier->email = $request->email;
        $supplier->address = $request->address;
        $supplier->save();

        return redirect()->back()->with('success', 'Supplier updated successfully!');
    }

    public function deleteSupplier($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return redirect()->route('admin.viewsupplier')->with('success', 'Supplier deleted successfully.');
    }

    public function addProduct()
    {
        return view('admin.addproduct');
    }

    public function createProduct(Request $request)
    {
        $product = new Product();
        $product->product_name = $request->product_name;
        $product->product_description = $request->product_description;
        $product->product_price = $request->product_price;
        $product->save();

        return redirect()->route('admin.addproduct')->with('success', 'Product added successfully.');
    }

    public function viewProduct()
    {
        $products = Product::all();
        return view('admin.viewproduct', compact('products'));
    }

    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.editproduct', compact('product'));
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->product_name = $request->product_name;
        $product->product_description = $request->product_description;
        $product->product_price = $request->product_price;
        $product->save();

        return redirect()->back()->with('success', 'Product updated successfully.');
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.viewproduct')->with('success', 'Product deleted successfully.');
    }

}
