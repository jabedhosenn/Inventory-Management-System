<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Products;

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
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('admin.addproduct', compact('categories', 'suppliers'));
    }

    public function createProduct(Request $request)
    {

        $product = new Products();
        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $product->product_image = $image_name;
        }
        $product->product_name = $request->product_name;
        $product->product_description = $request->product_description;
        $product->product_price = $request->product_price;
        $product->product_quantity = $request->product_quantity;
        $product->category_name = $request->category_name;
        $product->supplier_name = $request->supplier_name;
        $product->save();

        if ($request->hasFile('product_image') && $product->save()) {
            $request->product_image->move('images/products', $image_name);
        }

        return redirect()->route('admin.addproduct')->with('success', 'Product added successfully.');
    }

    public function viewProduct()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        $products = Products::all();
        return view('admin.viewproduct', compact('products'));
    }

    public function editProduct($id)
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        $product = Products::findOrFail($id);
        return view('admin.editproduct', compact('product', 'categories', 'suppliers'));
    }

    public function updateProduct(Request $request, $id)
    {

        $product = Products::findOrFail($id);
        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $product->product_image = $image_name;
        }
        $product->product_name = $request->product_name;
        $product->product_description = $request->product_description;
        $product->product_price = $request->product_price;
        $product->product_quantity = $request->product_quantity;
        $product->category_name = $request->category_name;
        $product->supplier_name = $request->supplier_name;
        $product->save();

        if ($request->hasFile('product_image') && $product->save()) {
            $request->product_image->move('images/products', $image_name);
        }

        return redirect()->back()->with('success', 'Product updated successfully.');
    }

    public function deleteProduct($id)
    {
        $product = Products::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.viewproduct')->with('success', 'Product deleted successfully.');
    }
}
