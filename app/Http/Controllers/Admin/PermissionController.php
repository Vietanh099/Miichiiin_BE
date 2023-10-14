<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionRequest;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json($this->get_permissions());
    }

    public function get_permissions ($has_permissions = [])
    {
        $my_role = Auth::guard('admins')->user()->getRoleNames();
        $level_role = Role::query()
            ->where('name', $my_role[0])
            ->value('level');
        $permissions = Permission::query()
            ->whereNotIn('id', $has_permissions)
            ->where('level', '<', $level_role)
            ->OrderByDesc('created_at')
            ->get(['id', 'name']);
        return $permissions;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PermissionRequest $request)
    {
        $permission = new Permission();
        $permission->fill($request->except('_token'));

        $permission->save();
        return response()->json($permission);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $permission = Permission::query()->find($id);
        return response()->json($permission);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PermissionRequest $request, string $id)
    {
        $permission = Permission::query()->find($id);
        $permission->fill($request->except('_token'));

        $permission->save();
        return response()->json($permission);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $permission = Permission::query()->find($id);
        $permission->delete();
        return response()->json([
            "message" => "delete success",
            "status" => Response::HTTP_OK
        ]);
    }
    public function updateState_permission(PermissionRequest $request, $id)
    {
        $locked = $request->input('status');
        // Perform the necessary logic to lock or unlock based on the $locked state
        $Permission = Permission::find($id);
        if ($Permission) {
            $Permission->status = $locked == 1 ? 1 : 0;
            $Permission->save();
            return response()->json([
                'message' => 'Toggle switch state updated successfully',
                'Permission' => $Permission,
            ]);
        }
        return response()->json([
            'message' => 'Permission not found',
        ], 404);
    }
}
