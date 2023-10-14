<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ComfortDetailRequest;
use App\Models\comfortDetail;
use Illuminate\Http\Request;

class ComfortDetailController extends Controller
{
    //
    public function index()
    {
        $comfortDetail = comfortDetail::all();
        return response()->json($comfortDetail);
    }
    public function show($id)
    {
        $comfortDetail = comfortDetail::find($id);
        return response()->json($comfortDetail);
    }
    public function store(ComfortDetailRequest $request)
    {
        // nếu như tồn tại file sẽ upload file
        $params = $request->except('_token');
        $comfortDetail  = comfortDetail::create($params);
        if ($comfortDetail->id) {
            return response()->json([
                'message' => $comfortDetail,
                'status' => 200
            ]);
        }
    }
    public function create()
    {
    }
    public function update(ComfortDetailRequest $request, $id)
    {

        $params = $request->except('_token');
        $comfortDetail = comfortDetail::find($id);
        if ($comfortDetail) {
            $comfortDetail->update($params);
            return response()->json([
                'message' => $comfortDetail,
                'status' => "Sửa Thành Công"
            ]);
        }
    }
    public function edit(ComfortDetailRequest $request, $id)
    {
        $comfortDetail = comfortDetail::find($id);
        $params = $request->except('_token');
        if ($comfortDetail) {
            return response()->json([
                'message' => $comfortDetail,
            ]);
        }
    }
    public function destroy($id)
    {
        $comfortDetail = comfortDetail::find($id);
        if ($comfortDetail) {
            $comfortDetail->delete();
            return response()->json([
                'message' => "Delete success",
                'status' => 200
            ]);
        }
        return response()->json($comfortDetail);
    }
}
