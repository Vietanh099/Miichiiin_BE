<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomRequest;
use App\Models\room;
use Illuminate\Http\Request;

class roomsController extends Controller
{
    //
    public function index()
    {
        $room = room::select('rooms.*', 'hotels.name as name_hotel','category_rooms.name as name_category')
        ->join('hotels', 'rooms.id_hotel', '=', 'hotels.id')
        ->join('category_rooms', 'rooms.id_cate', '=', 'category_rooms.id')
        ->get();
        return response()->json($room);
    }
    public function room_cate($cate)
    {
        $room = room::get()->where('id_cate', "=" , $cate);
        return response()->json($room);
    }
    public function show($id)
    {
        $room = room::find($id);
        return response()->json($room);
    }
    public function store(RoomRequest $request)
    {
        // nếu như tồn tại file sẽ upload file
        $params = $request->except('_token');
        $room  = room::create($params);
        if ($room->id) {
            return response()->json([
                'message' => $room,
                'status' => 200
            ]);
        }
    }
    public function create()
    {
    }
    public function update(RoomRequest $request, $id)
    {
        $params = $request->except('_token');
        $room = room::find($id);
        if ($room) {
            $room->update($params);
            return response()->json([
                'message' => $room,
                'status' => "Sửa Thành Công"
            ]);
        }
    }
    public function edit(RoomRequest $request, $id)
    {
        $room = room::find($id);
        $params = $request->except('_token');
        if ($room) {
            return response()->json([
                'message' => $room,
            ]);
        }
    }
    public function destroy($id)
    {
        $room = room::find($id);
        if ($room) {
            $room->delete();
            return response()->json([
                'message' => "Delete success",
                'status' => 200
            ]);
        }
        return response()->json($room);
    }
    public function updateState(RoomRequest $request, $id)
    {
        $locked = $request->input('status');
        // Perform the necessary logic to lock or unlock based on the $locked state
        $room = Room::find($id);
        if ($room) {
            $room->status = $locked == 1 ? 1 : 0;
            $room->save();
            return response()->json([
                'message' => 'Toggle switch state updated successfully',
                'room' => $room,
            ]);
        }
        return response()->json([
            'message' => 'Room not found',
        ], 404);
    }
}
