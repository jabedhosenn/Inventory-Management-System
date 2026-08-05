<?php

namespace App\Http\Controllers\Api\V1\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::orderByDesc('id')->get();
            return response()->json([
                'success' => true,
                'message' => 'Categories retrieved successfully',
                'data' => $categories
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while retrieving categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
                'description' => ['nullable', 'string'],
                'status' => ['boolean'],
            ]);

            $category = Category::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Category created successfully',
                'data' => $category
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while creating category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(int $id)
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Category retrieved successfully',
                'data' => $category
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while retrieving category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, int $id)
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found',
                ], 404);
            }

            $validated = $request->validate([
                'name' => ['sometimes', 'required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
                'description' => ['sometimes', 'nullable', 'string'],
                'status' => ['sometimes', 'boolean'],
            ]);

            $category->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully',
                'data' => $category
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while updating category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(int $id)
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found',
                ], 404);
            }

            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully',
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while deleting category',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
