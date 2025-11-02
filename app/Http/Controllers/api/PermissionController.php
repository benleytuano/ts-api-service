<?php

namespace App\Http\Controllers\api;

use App\Models\Permission;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    /**
     * Get all permissions
     * 
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $permissions = Permission::select('id', 'name')
                ->orderBy('name', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $permissions,
                'message' => 'Permissions retrieved successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve permissions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific permission
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $permission = Permission::select('id', 'name')
                ->with('roles:id,name')
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $permission,
                'message' => 'Permission retrieved successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve permission',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

