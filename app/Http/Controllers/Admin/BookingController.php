<?php

namespace App\Http\Controllers\Admin;

use App\Models\booking;
use App\Models\bookingDetail;
use App\Models\room;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BookingController extends Controller
{
    public function index()
    {
        $auth_admin = Auth::guard('admins')->user();
        if ($auth_admin->id_hotel == null) {
            $booking = booking::query()
                ->select('bookings.*', 'users.name as name_user',)
                ->leftJoin('users', 'bookings.id_user', '=', 'users.id')
                ->get();
            return response()->json($booking);
        }
        $booking = booking::query()
                    ->select('bookings.*', 'users.name as name_user',)
                    ->leftJoin('users', 'bookings.id_user', '=', 'users.id')
                    ->where('id_hotel', $auth_admin->id_hotel)
                    ->get();
        return response()->json($booking);
    }

    public function show($id)
    {
        $booking_detail = get_detail_booking($id);
        return response()->json($booking_detail["message"], $booking_detail["status"]);
    }
    public function store(BookingRequest $request)
    {
//        return view('mails.mail_booking');
        // Create Booking
        $auth_admin = Auth::guard('admins')->user();
        $data = $request->except('_token');
        $reponse_data = create_booking($auth_admin->id_hotel, $data);
        return response()->json($reponse_data['message'], $reponse_data['status']);
    }
    public function create()
    {
    }
    protected function update(Request $request, $id)
    {
        $booking = booking::query()->find($id);
        if ($booking == null) {
            return response()->json(
                "Không tìm thấy đơn đặt hàng"
            , Response::HTTP_BAD_REQUEST);
        }
        $booking->fill($request->except(['_token', 'cart', 'slug']));
//        $promotion = $request->promotion ?? null;
        $flag = $request->flag;
        $cart = $request->cart;

        if ($flag) {
            $booking_d_record = [];
            $booking_detail = bookingDetail::query()
                ->select('*')
                ->where('id_booking', '=', $booking->id)
                ->get();
            if (count($booking_detail) <= 0) {
                return response()->json(
                    ['message' => "Không tìm thấy đơn đặt phòng"]
                )->setStatusCode(Response::HTTP_BAD_REQUEST);
            }

            for ($i = 0; $i < count($cart); $i++) {
                if ($cart[$i]['id_room'] == null) {
                    return response()->json("Bạn chưa chọn phòng", Response::HTTP_BAD_REQUEST);
                }
                $id_cate = room::query()
                    ->select('hotel_categories.id_cate')
                    ->join('hotel_categories', 'rooms.id_hotel_cate', '=', 'hotel_categories.id')
                    ->where('rooms.id', $cart[$i]['id_room'])
                    ->pluck('hotel_categories.id_cate')
                    ->first();

                if (empty($cart[$i]['services'])) {
                    $booking_d_record[] = [
                        'id_booking' => $booking->id,
                        'id_room' => $cart[$i]['id_room'],
                        'id_cate' => $id_cate,
                        'id_services' => -1,
//                        'id_promotions' => $promotion
                    ];
                    continue;
                }

                foreach ($cart[$i]['services'] as $service) {
                    $booking_d_record[] = [
                        'id_booking' => $booking->id,
                        'id_room' => $cart[$i]['id_room'],
                        'id_cate' => $id_cate,
                        'id_services' => $service['id_service'],
//                        'id_promotions' => $promotion,
                        'quantity_service' => $service['quantity'],
                    ];
                }
            }
            foreach ($booking_detail as $detail_record) {
                $detail_record->delete();
            }
            bookingDetail::query()->insert($booking_d_record);
        }

        $booking->save();
        return response()->json($booking)
            ->setStatusCode(Response::HTTP_OK);
    }
    public function edit(BookingRequest $request, $id)
    {
        $booking = booking::find($id);
        $params = $request->except('_token');
        if ($booking) {
            return response()->json([
                'message' => $booking,
            ]);
        }
    }
    public function destroy($id)
    {
        $booking = booking::find($id);
        if ($booking) {
            $booking->delete();
            return response()->json([
                'message' => "Delete success",
                'status' => 200
            ]);
        }
        return response()->json($booking);
    }
    public function updateState_booking(BookingRequest $request, $id)
    {
        $locked = $request->input('status');
        // Perform the necessary logic to lock or unlock based on the $locked state
        $booking = booking::find($id);
        if ($booking) {
            $booking->status = $locked == 1 ? 1 : 0;
            $booking->save();
            return response()->json([
                'message' => 'Toggle switch state updated successfully',
                'booking' => $booking,
            ]);
        }
        return response()->json([
            'message' => 'booking not found',
        ], 404);
    }
}
