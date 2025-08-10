<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * Get all categories
     * 
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $categories = Category::select('id', 'name')
                ->orderBy('name', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $categories,
                'message' => 'Categories retrieved successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get only active categories (if you have an 'is_active' column)
     * 
     * @return JsonResponse
     */
    public function active(): JsonResponse
    {
        try {
            $categories = Category::select('id', 'name')
                ->where('is_active', true) // Assuming you have this column
                ->orderBy('name', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $categories,
                'message' => 'Active categories retrieved successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve active categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get categories with ticket counts
     * 
     * @return JsonResponse
     */
    public function withTicketCounts(): JsonResponse
    {
        try {
            $categories = Category::select('id', 'name')
                ->withCount('tickets')
                ->orderBy('name', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $categories,
                'message' => 'Categories with ticket counts retrieved successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve categories with counts',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}