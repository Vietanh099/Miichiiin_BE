<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\RoleRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleControler extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $level_role = get_current_level();
        $roles = Role::query()
            ->where('level', '<', $level_role)
            ->OrderByDesc('created_at')
            ->get(['id', 'name', 'level', 'updated_at', 'created_at']);
        return response()->json($roles);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function assign_permission (Request $request, string $id_role=null, array $permissons=null)
    {
        $id_role = $id_role ?? $request->id_role;
        $permissons = $permissons ?? $request->list_permissions;
        $role = Role::query()->find($id_role);
        $permissions = Permission::whereIn('id', $permissons)->get();

        $role->syncPermissions($permissions);

        return response()->json([
            "role_name" => $role->name,
            "permissions"  => $permissions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        $role = new Role();
        $role->fill($request->except('_token', 'permissons'));
        $level_role = get_current_level();
        $role->level = $level_role - 1;
        $role->save();

        $request['id_role'] = $role->id;
        $this->assign_permission($request, $request['id_role'], $request->permissions);

        return response()->json($role);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // tạo đối tượng của permission
        $permissons_class = new PermissionController();

        // tìm role theo $id
        $role = Role::query()->find($id);
        // Các permission mà role đã có
        $had_permissions = $role->permissions->pluck('id', 'name');

        $response_list = [];
        $list_id = [];
        foreach ($had_permissions as $key => $permission) {
            $list_id[] = $permission;
            $response_list[] = [
                'id' => $permission,
                'name' => $key
            ];
        }

        // Các permission mà role chưa có
        $per = $permissons_class->get_permissions($list_id);

        // Response
        $response = [
            "name" => $role->name,
            "had_permissions" => $response_list,
            "list_permissions" => $per
        ];

        return response()->json($response);
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
    public function update(RoleRequest $request, string $id)
    {
        $role = Role::query()->find($id);
        $role->fill($request->except('_token'));
        $request['id_role'] = $role->id;
        $this->assign_permission($request, $request['id_role'], $request->permissions);
        $role->save();
        return response()->json($role);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::query()->find($id);
        if ($role == null) {
            return response()->json([
                "message" => "role not found",
                "status" => Response::HTTP_NOT_FOUND
            ]);
        }
        $role->delete();
        return response()->json([
            "message" => "delete success",
            "status" => Response::HTTP_OK
        ]);
    }
    public function updateState_role(RoleRequest $request, $id)
    {
        $locked = $request->input('status');
        // Perform the necessary logic to lock or unlock based on the $locked state
        $Role = Role::find($id);
        if ($Role) {
            $Role->status = $locked == 1 ? 1 : 0;
            $Role->save();
            return response()->json([
                'message' => 'Toggle switch state updated successfully',
                'Role' => $Role,
            ]);
        }
        return response()->json([
            'message' => 'Role not found',
        ], 404);
    }
}
