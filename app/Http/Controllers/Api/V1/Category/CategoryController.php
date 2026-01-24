<?php

namespace App\Http\Controllers\Api\V1\Category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::orderByDesc('created_at')->get();
            return response()->json([
                'status' => 'success',
                'message' => 'Categories retrieved successfully.',
                'data' => $categories
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching categories.',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'status' => ['boolean'],
            ]);
            $category = Category::create($validated);
            return response()->json([
                'status' => 'success',
                'message' => 'Category created successfully.',
                'data' => $category
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while creating the category.',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(int $id)
    {
        try {
            $category = Category::find($id);
            if (!$category) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Category not found.',
                ], 404);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Category fetched successfully.',
                'data' => $category
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching the category.',
                'errors' => $e->getMessage(),
            ], 404);
        }
    }

    public function update(Request $request, int $id)
    {
        try {
            $category = Category::find($id);
            if (!$category) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Category not found.',
                ], 404);
            }
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'status' => ['boolean'],
            ]);
            $category->update($validated);
            return response()->json([
                'status' => 'success',
                'message' => 'Category updated successfully.',
                'data' => $category
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating the category.',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(int $id)
    {
        try {
            $category = Category::find($id);
            if (!$category) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Category not found.',
                ], 404);
            }
            $category->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Category deleted successfully.',
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting the category.',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
}
