<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VoucherRequest;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $voucher = Voucher::orderByDesc('created_at')->get();
        return response()->json($voucher);
    }
    public function list_vourcher()
    {
        $voucher = Voucher::orderByDesc('created_at')->active()->get();
        return response()->json($voucher);
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
    public function store(VoucherRequest $request)
    {
        $voucher = new Voucher();

        $voucher->fill($request->except('_token'));

        $voucher->image = upload_file('image', $request->file('image'));
        $voucher->save();

        return response()->json($voucher);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $voucher = Voucher::query()->find($id);
        return response()->json($voucher);
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
    public function update(VoucherRequest $request, string $id)
    {
        $voucher = Voucher::query()->find($id);

        $oldImg = $voucher->image;

        $voucher->fill($request->except('_token'));

        if ($request->file('image')) {
            $voucher->image = upload_file('image', $request->file('image'));
            delete_file($oldImg);
        }

        $voucher->save();

        return response()->json($voucher);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $voucher = Voucher::query()->find($id);

        if ($voucher == null) {
            return response()->json([
                "message" => "Voucher not found",
                "status" => Response::HTTP_NOT_FOUND
            ]);
        }
        $voucher->delete();
        delete_file($voucher->image);
        return response()->json([
            "message" => "Delete success",
            "status" => Response::HTTP_OK
        ]);
    }
    public function updateState_voucher(VoucherRequest $request, $id)
    {
        $locked = $request->input('status');
        // Perform the necessary logic to lock or unlock based on the $locked state
        $Voucher = Voucher::find($id);
        if ($Voucher) {
            $Voucher->status = $locked == 1 ? 1 : 0;
            $Voucher->save();
            return response()->json([
                'message' => 'Toggle switch state updated successfully',
                'Voucher' => $Voucher,
            ]);
        }
        return response()->json([
            'message' => 'Room not found',
        ], 404);
    }
}
