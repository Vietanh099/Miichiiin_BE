<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PermissionDetail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PermissionDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = PermissionDetail::orderByDesc('created_at')->get();
        return response()->json($data);
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
    public function store(Request $request)
    {
        $permission_d = new PermissionDetail();

        $permission_d->fill($request->except('_token'));
        $permission_d->save();

        return response()->json($permission_d);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $permission_d = PermissionDetail::query()->find($id);
        return response()->json($permission_d);
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
    public function update(Request $request, string $id)
    {
        $permission = PermissionDetail::query()->find($id);

        $permission->fill($request->except('_token'));

        $permission->save();

        return response()->json($permission);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $permission_d = PermissionDetail::query()->find($id);

        if ($permission_d == null) {
            return response()->json([
                "message" => "Permission detail not found",
                "status" => Response::HTTP_NOT_FOUND
            ]);
        }

        $permission_d->delete();
        return response()->json([
            "message" => "Delete success",
            "status" => Response::HTTP_OK
        ]);
    }
}
