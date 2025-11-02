<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;


class UserController extends Controller
{
    /**
     * Get all users with their roles and departments
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $users = User::select('id', 'first_name', 'last_name', 'email', 'role_id', 'department_id')
                ->with(['role:id,name', 'department:id,name'])
                ->orderBy('first_name', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $users,
                'message' => 'Users retrieved successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve users',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific user with their role and department
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $user = User::select('id', 'first_name', 'last_name', 'email', 'role_id', 'department_id')
                ->with(['role:id,name', 'department:id,name'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'User retrieved successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get users by role
     *
     * @param string $roleName
     * @return JsonResponse
     */
    public function byRole($roleName): JsonResponse
    {
        try {
            $users = User::select('id', 'first_name', 'last_name', 'email', 'role_id', 'department_id')
                ->with(['role:id,name', 'department:id,name'])
                ->whereHas('role', function ($query) use ($roleName) {
                    $query->where('name', $roleName);
                })
                ->orderBy('first_name', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $users,
                'message' => "Users with role '{$roleName}' retrieved successfully"
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve users',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get users by department
     *
     * @param int $departmentId
     * @return JsonResponse
     */
    public function byDepartment($departmentId): JsonResponse
    {
        try {
            $users = User::select('id', 'first_name', 'last_name', 'email', 'role_id', 'department_id')
                ->with(['role:id,name', 'department:id,name'])
                ->where('department_id', $departmentId)
                ->orderBy('first_name', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $users,
                'message' => 'Users retrieved successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve users',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
