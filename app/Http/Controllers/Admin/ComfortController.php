<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ComfortRequest;
use App\Models\Comfort;
use Illuminate\Http\Response;

class ComfortController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comforts = Comfort::orderByDesc('created_at')->get();
        return response()->json($comforts);
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
    public function store(ComfortRequest $request)
    {
        $comfort = new Comfort();
        $comfort->fill($request->except('_token'));

        $comfort->save();
        return response()->json($comfort);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $comfort = Comfort::query()->find($id);
        return response()->json($comfort);
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
    public function update(ComfortRequest $request, string $id)
    {
        $comfort = Comfort::query()->find($id);
        $comfort->fill($request->except('_token'));

        $comfort->save();
        return response()->json($comfort);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $comfort = Comfort::query()->find($id);
        $comfort->delete();
        return response()->json([
            "message" => "delete success",
            "status" => Response::HTTP_OK
        ]);
    }
    public function updateState_comfort(ComfortRequest $request, $id)
    {
        $locked = $request->input('status');
        // Perform the necessary logic to lock or unlock based on the $locked state
        $Comfort = Comfort::find($id);
        if ($Comfort) {
            $Comfort->status = $locked == 1 ? 1 : 0;
            $Comfort->save();
            return response()->json([
                'message' => 'Toggle switch state updated successfully',
                'Comfort' => $Comfort,
            ]);
        }
        return response()->json([
            'message' => 'Room not found',
        ], 404);
    }
    public function comfort_cate($id)
    {
        $comforts = Comfort::select('comforts.*',
        )
        ->join('comfort_details','comforts.id','=','comfort_details.id_comfort')
        ->join('category_rooms','comfort_details.id_cate_room','=','category_rooms.id')
        ->where('id_cate_room',$id)
        ->where('comforts.status',"=",1)

        ->get();
        return response()->json($comforts);
    }
}
