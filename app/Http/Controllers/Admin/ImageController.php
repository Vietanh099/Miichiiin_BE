<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\ImageRequest;
use App\Models\image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    //
    public function index()
    {
        $image = image::all();
        return response()->json($image);
    }
    public function show($id)
    {
        $image = image::find($id);
        return response()->json($image);
    }
    public function store(ImageRequest $request)
    {
        // nếu như tồn tại file sẽ upload file
        $params = $request->except('_token');
        $params['image'] = upload_file('image', $request->file('image'));
        $image  = image::create($params);
        if ($image->id) {
            return response()->json([
                'message' => $image,
                'status' => 200
            ]);
        }
    }
    public function create()
    {
    }
    public function update(ImageRequest $request, $id)
    {

        $params = $request->except('_token');
        $image = image::find($id);
        if ($request->hasFile('image') && $request->file('image')) {
            $del = delete_file($request->image);
            if ($del) {
                $params['image'] = upload_file('image', $request->file('image'));
            }
        } else {
            $params['image'] = $image->image;
        }
        if ($image) {
            $image->update($params);
            return response()->json([
                'message' => $image,
                'status' => "Sửa Thành Công"
            ]);
        }
    }
    public function edit(ImageRequest $request, $id)
    {
        $image = image::find($id);
        $params = $request->except('_token');
        if ($image) {
            return response()->json([
                'message' => $image,
            ]);
        }
    }
    public function destroy($id)
    {
        $image = image::find($id);
        if ($image) {
            $image->delete();
            return response()->json([
                'message' => "Delete success",
                'status' => 200
            ]);
        }
        return response()->json($image);
    }
}
