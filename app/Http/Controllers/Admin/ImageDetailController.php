<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\ImageDetailRequest;
use App\Models\imageDetail;
use Illuminate\Http\Request;

class ImageDetailController extends Controller
{
    //
    public function index()
    {
        $imageDetail = imageDetail::all();
        return response()->json($imageDetail);
    }
    public function show($id)
    {
        $imageDetail = imageDetail::find($id);
        return response()->json($imageDetail);
    }
    public function store(ImageDetailRequest $request)
    {
        // nếu như tồn tại file sẽ upload file
        $params = $request->except('_token');
        $imageDetail  = imageDetail::create($params);
        if ($imageDetail->id) {
            return response()->json([
                'message' => $imageDetail,
                'status' => 200
            ]);
        }
    }
    public function create()
    {
    }
    public function update(ImageDetailRequest $request, $id)
    {

        $params = $request->except('_token');
        $imageDetail = imageDetail::find($id);
        if ($imageDetail) {
            $imageDetail->update($params);
            return response()->json([
                'message' => $imageDetail,
                'status' => "Sửa Thành Công"
            ]);
        }
    }
    public function edit(ImageDetailRequest $request, $id)
    {
        $imageDetail = imageDetail::find($id);
        $params = $request->except('_token');
        if ($imageDetail) {
            return response()->json([
                'message' => $imageDetail,
            ]);
        }
    }
    public function destroy($id)
    {
        $imageDetail = imageDetail::find($id);
        if ($imageDetail) {
            $imageDetail->delete();
            return response()->json([
                'message' => "Delete success",
                'status' => 200
            ]);
        }
        return response()->json($imageDetail);
    }
}
