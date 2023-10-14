<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use App\Models\bookingDetail;

class BookingDetailController extends Controller
{
    //
    public function index()
    {
        $bookingDetail = bookingDetail::all();
        return response()->json($bookingDetail);
    }
    public function show($id)
    {
        $bookingDetail = bookingDetail::find($id);
        return response()->json($bookingDetail);
    }
    public function store(BookingRequest $request)
    {
        // nếu như tồn tại file sẽ upload file
        $params = $request->except('_token');
        $bookingDetail  = bookingDetail::create($params);
        if ($bookingDetail->id) {
            return response()->json([
                'message' => $bookingDetail,
                'status' => 200
            ]);
        }
    }
    public function create()
    {
    }
    public function update(BookingRequest $request, $id)
    {
        $params = $request->except('_token');
        $bookingDetail = bookingDetail::find($id);
        if ($bookingDetail) {
            $bookingDetail->update($params);
            return response()->json([
                'message' => $bookingDetail,
                'status' => "Update Success"
            ]);
        }
    }
    public function edit(BookingRequest $request, $id)
    {
        $bookingDetail = bookingDetail::find($id);
        $params = $request->except('_token');
        if ($bookingDetail) {
            return response()->json([
                'message' => $bookingDetail,
            ]);
        }
    }
    public function destroy($id)
    {
        $bookingDetail = bookingDetail::find($id);
        if ($bookingDetail) {
            $bookingDetail->delete();
            return response()->json([
                'message' => "Delete success",
                'status' => 200
            ]);
        }
        return response()->json($bookingDetail);
    }
    public function booking_detail_list($id)
    {

        // get all booking
        $bookingDetail = bookingDetail::select(
            'booking_details.*',
            'bookings.name as namebooking',
            'bookings.check_in as check_in',
            'bookings.check_out as check_out',
            'bookings.people_quantity as people_quantity',
            'bookings.total_amount as total_amount',
            'bookings.status as status',
            'bookings.nationality as nationality',
            'bookings.cccd as cccd',
            'bookings.phone as phone',
            'bookings.email as email',
            'bookings.message as message',
            'rooms.name as phong',
            'category_rooms.name as loaiphong',
            'vouchers.name as khuyenmai',
            'services.name as dichvu',
        )
            ->leftJoin('bookings', 'booking_details.id_booking', '=', 'bookings.id')
            ->leftJoin('services', 'services.id', '=', 'booking_details.id_services')
            ->leftJoin('vouchers', 'vouchers.id', '=', 'booking_details.id_promotions')
            ->leftJoin('rooms', 'rooms.id', '=', 'booking_details.id_room')
            ->leftJoin('category_rooms', 'category_rooms.id', '=', 'booking_details.id_cate')
            ->leftJoin('users', 'users.id', '=', 'bookings.id_user')
            ->groupBy(
            )
            ->where('users.id','=',$id)
            ->distinct()
            ->get()
            ->groupBy('id')
            ->map(function ($group) {
                return $group->first();
            })
            ->values();

        return response()->json($bookingDetail);
    }
}
