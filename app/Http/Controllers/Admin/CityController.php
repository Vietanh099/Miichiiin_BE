<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CityRequest;
use App\Models\city;
use App\Http\Controllers\Controller;

class CityController extends Controller
{
    //
    public function index()
    {
        $city = city::all();
        return response()->json($city);
    }
    public function show($id)
    {
        $city = city::find($id);
        return response()->json($city);
    }
    public function store(CityRequest $request)
    {
        // nếu như tồn tại file sẽ upload file
        $params = $request->except('_token');
        $city  = city::create($params);
        if ($city->id) {
            return response()->json([
                'message' => $city,
                'status' => 200
            ]);
        }
    }
    public function create()
    {
    }
    public function update(CityRequest $request, $id)
    {

        $params = $request->except('_token');
        $city = city::find($id);
        if ($city) {
            $city->update($params);
            return response()->json([
                'message' => $city,
                'status' => "Sửa Thành Công"
            ]);
        }
    }
    public function edit(CityRequest $request, $id)
    {
        $city = city::find($id);
        $params = $request->except('_token');
        if ($city) {
            return response()->json([
                'message' => $city,
            ]);
        }
    }
    public function destroy($id)
    {
        $city = city::find($id);
        if ($city) {
            $city->delete();
            return response()->json([
                'message' => "Delete success",
                'status' => 200
            ]);
        }
        return response()->json($city);
    }
}
