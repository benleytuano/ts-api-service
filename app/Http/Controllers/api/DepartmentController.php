<?php

namespace App\Http\Controllers\api;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class DepartmentController extends Controller
{
    /**
     * Get all departments
     * 
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $departments = Department::select('id', 'name')
                ->orderBy('name', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $departments,
                'message' => 'Departments retrieved successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve departments',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific department with its locations
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $department = Department::with('locations:id,name,department_id')
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $department,
                'message' => 'Department retrieved successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve department',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get departments with user counts
     * 
     * @return JsonResponse
     */
    public function withUserCounts(): JsonResponse
    {
        try {
            $departments = Department::select('id', 'name')
                ->withCount('users')
                ->orderBy('name', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $departments,
                'message' => 'Departments with user counts retrieved successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve departments with counts',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

