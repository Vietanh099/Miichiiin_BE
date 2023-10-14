<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::orderByDesc('created_at')->get();
        return response()->json($banners);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $banner = new Banner();
        $banner->fill($request->except(['re_password', '_token']));
        $uploadedImage = Cloudinary::upload($request->image->getRealPath());
        $banner->image = $uploadedImage->getSecurePath();
        $banner->save();
        return response()->json($banner);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $banner = Banner::find($id);
        return response()->json($banner);
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
        $banner = Banner::query()->find($id);
        $oldImg = $banner->image;
        $banner->fill($request->except(['re_password', '_token']));
        if ($request->file('image')) {
            if ($oldImg) {
                Cloudinary::destroy($oldImg);
            }
            $uploadedImage = Cloudinary::upload($request->image->getRealPath());
            $banner->image = $uploadedImage->getSecurePath();
        }

        $banner->save();

        return response()->json($banner);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $banner = Banner::query()->find($id);
        $oldImg = $banner->image;
        if ($oldImg) {
            Cloudinary::destroy($oldImg);
        }
        $banner->delete();
        return response()->json([
            "message" => "Delete success",
            "status" => 200
        ]);
    }
}
