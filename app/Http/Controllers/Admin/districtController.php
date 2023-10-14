<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DistrictRequest;
use App\Models\district;

class districtController extends Controller
{

    public function index()
    {
        $district = district::all();
        return response()->json($district);
    }
    public function show($id)
    {
        $district = district::find($id);
        return response()->json($district);
    }
    public function store(DistrictRequest $request)
    {
        // nếu như tồn tại file sẽ upload file
        $params = $request->except('_token');
        $district  = district::create($params);
        if ($district->id) {
            return response()->json([
                'message' => $district,
                'status' => 200
            ]);
        }
    }
    public function create(DistrictRequest $request, $id)
    {
        $district = district::find($id);
        $params = $request->except('_token');
        if ($district) {
            $district->update($params);
            return response()->json([
                'message' => $district,
                'status' => "Sửa Thành Công"
            ]);
        }
    }
    public function update(DistrictRequest $request, $id)
    {
        $district = district::find($id);
        $params = $request->except('_token');
        if ($district) {
            $district->update($params);
            return response()->json([
                'message' => $district,
                'status' => "Sửa Thành Công"
            ]);
        }
    }
    public function edit(DistrictRequest $request, $id)
    {
        $district = district::find($id);
        $params = $request->except('_token');
        if ($district) {
            return response()->json([
                'message' => $district,
            ]);
        }
    }
    public function destroy($id)
    {
        $district = district::find($id);
        if ($district) {
            $district->delete();
            return response()->json([
                'message' => "Delete success",
                'status' => 200
            ]);
        }
        return response()->json($district);
    }
    public function updateState_district(DistrictRequest $request, $id)
    {
        $locked = $request->input('status');
        // Perform the necessary logic to lock or unlock based on the $locked state
        $district = district::find($id);
        if ($district) {
            $district->status = $locked == 1 ? 1 : 0;
            $district->save();
            return response()->json([
                'message' => 'Toggle switch state updated successfully',
                'district' => $district,
            ]);
        }
        return response()->json([
            'message' => 'district not found',
        ], 404);
    }
}
