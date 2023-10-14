<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Models\Service;
use App\Models\ServiceDetail;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $service = Service::orderByDesc('created_at')->get();
        return response()->json($service);
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
    public function store(ServiceRequest $request)
    {
        $service = new Service();

        $service->fill($request->except('_token'));

        $uploadedImage = Cloudinary::upload($request->image->getRealPath());
        $service->image = $uploadedImage->getSecurePath();
        $service->save();

        return response()->json($service);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $service = Service::query()->find($id);
        return response()->json($service);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $service = Service::query()->find($id);
        $oldImg = $service->image;

        $service->fill($request->except('_token'));

        if ($request->hasFile('image') && $request->file('image')) {
            if ($oldImg) {
                Cloudinary::destroy($oldImg);
            }
            $uploadedImage = Cloudinary::upload($request->image->getRealPath());
            $service->image = $uploadedImage->getSecurePath();
        }
        $service->save();

        return response()->json($service);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $service = Service::query()->find($id);
        $oldImg = $service->image;

        if($service){
            if ($oldImg) {
                Cloudinary::destroy($oldImg);
            }
        $service->delete();

        }

        return response()->json([
            "message" => "Delete success",
            "status" => 200
        ]);
    }
    public function list_services_hotel($id)
    {
        $service = Service::
        leftJoin('service_details', 'service_details.id_service', '=', 'services.id')
        ->leftJoin('hotels', 'hotels.id', '=', 'service_details.id_hotel')
        ->select('service_details.id_hotel', 'services.*')
        ->active()
        ->where('service_details.id_hotel', "=",$id)
        ->distinct()
        ->get();
        return response()->json($service);
    }
    public function updateState_services(ServiceRequest $request, $id)
    {
        $locked = $request->input('status');
        $service = Service::find($id);
        if ($service) {
            $service->status = $locked == 1 ? 1 : 0;
            $service->save();
            return response()->json([
                'message' => 'switch state updated successfully',
                'service' => $service,
            ]);
        }
        return response()->json([
            'message' => 'service not found',
        ], 404);
    }



}
