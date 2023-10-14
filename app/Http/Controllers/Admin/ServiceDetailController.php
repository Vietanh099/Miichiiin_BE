<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceDetail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ServiceDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = ServiceDetail::orderByDesc('created_at')->get();
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
        $service_d = new ServiceDetail();

        $service_d->fill($request->except('_token'));
        $service_d->save();

        return response()->json($service_d);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $service_d = ServiceDetail::query()->find($id);
        return response()->json($service_d);
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
        $service_d = ServiceDetail::query()->find($id);

        $service_d->fill($request->except('_token'));

        $service_d->save();

        return response()->json($service_d);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $service_d = ServiceDetail::query()->find($id);
        $service_d->delete();
        return response()->json([
            "message" => "delete success",
            "status" => Response::HTTP_OK
        ]);
    }
}
