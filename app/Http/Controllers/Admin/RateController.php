<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RateRequest;
use App\Models\Rate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rates = Rate::select('rates.*', 'users.name as user_name','category_rooms.name as name_category')
        ->join('users', 'rates.id_user', '=', 'users.id')
        ->join('category_rooms', 'rates.id_category', '=', 'category_rooms.id')
        ->get();
        return response()->json($rates);
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
    public function store(RateRequest $request)
    {
        $rate = new Rate();
        $rate->fill($request->except('_token'));

        $rate->save();
        return response()->json($rate);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rate = Rate::query()->find($id);
        return response()->json($rate);
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
    public function update(RateRequest $request, string $id)
    {
        $rate = Rate::query()->find($id);
        $rate->fill($request->except('_token'));

        $rate->save();
        return response()->json($rate);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rate = Rate::query()->find($id);
        $rate->delete();
        return response()->json([
            "message" => "delete success",
            "status" => Response::HTTP_OK
        ]);
    }
    public function comment_cate($id)
    {
        $comments = Rate::select(
            'rates.*',
            'category_rooms.name as category_name',
            'users.name as user_name',
            'users.email as user_email'
        )
            ->join('category_rooms', 'rates.id_category', '=', 'category_rooms.id')
            ->join('users', 'users.id', '=', 'rates.id_user')
            ->where('category_rooms.id', $id)
            ->where('rates.status',"=", 1)
            ->get();

        // sau khi mình gửi cho bên front thông tin comment
        // bên front sẽ lấy thời gian hiện tại comment
        //   trừ đi thời gian comment để lấy thời gian comment  ví dụ (8h trước)
        return response()->json($comments);
    }

    public function updateState_rate(RateRequest $request, $id)
    {
        $locked = $request->input('status');
        // Perform the necessary logic to lock or unlock based on the $locked state
        $Rate = Rate::find($id);
        if ($Rate) {
            $Rate->status = $locked == 1 ? 1 : 0;
            $Rate->save();
            return response()->json([
                'message' => 'Toggle switch state updated successfully',
                'Rate' => $Rate,
            ]);
        }
        return response()->json([
            'message' => 'Rate not found',
        ], 404);
    }
}
