<?php

use App\Mail\Sendmail;
use App\Models\booking;
use App\Models\bookingDetail;
use App\Models\categoryRoom;
use App\Models\room;
use App\Models\Service;
use App\Models\User;
use App\Models\Voucher;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

if (!function_exists('upload_file')) {
    function upload_file($folder, $file) {
        return 'storage/' . Storage::put($folder, $file);
    }
}

if (!function_exists('delete_file')) {
    function delete_file($pathFile) {
        $pathFile = str_replace('storage/', '', $pathFile);
        return Storage::exists($pathFile) ? Storage::delete($pathFile) : null;
    }
}

function get_current_level () {
    $my_role = Auth::guard('admins')->user()->getRoleNames();
    $level_role = Role::query()
        ->where('name', $my_role[0])
        ->value('level');
    return $level_role;
}

function set_fail ($model) {
    $model->status = 1;
}

function set_success_booking ($model) {
    $model->status = 3;
}

function create_booking($id_hotel, $data, $id_user=null) {
    // Create Booking
    $booking = new booking();
    $booking->fill($data);
    $booking->id_hotel = $id_hotel;
    $booking->id_user = $id_user;
    $sync_phone = sync_phone($booking->phone);
    if ($sync_phone == null) {
        return [
            "message" => "Số điện thoại không hợp lệ",
            "status" => Response::HTTP_BAD_REQUEST
        ];
    }
    $booking->phone = $sync_phone;

    $slug = "MiiChi-Booking-" . strtolower(Str::random(2)) . rand(100, 999);
    $booking->slug = $slug;

    $check_in = $data['check_in'];
    $check_out = $data['check_out'];

    $list_booking = booking::query()
        ->select('id')
        ->where('check_out', '>=', $check_in)
        ->orWhere('check_in', '<=', $check_out)
        ->whereNotIn('status', [2, 3])
        ->get();

    $room_ignore = bookingDetail::query()
        ->select('id_room')
        ->whereIn('id_booking', $list_booking)
        ->distinct()
        ->get();

    $booking->save();
    $cart = $data['cart'];
    if (!isset($cart)) {
        set_fail($booking);
        return [
            "message" => 'Không tìm thấy tham số cart',
            "status" => Response::HTTP_BAD_REQUEST
        ];
    }

    $cart = collect($cart)->sortBy('id_cate')->values()->all();

    $booking_d_record = [];
    $j = 0;
    $reset = 0;
    for ($i = 0; $i < count($cart); $i++) {
        $list_room = room::query()
            ->select('rooms.id')
            ->join('hotel_categories', 'rooms.id_hotel_cate', '=', 'hotel_categories.id')
            ->whereNotIn('rooms.id', $room_ignore)
            ->where('hotel_categories.id_cate', $cart[$i]['id_cate'])
            ->where('hotel_categories.id_hotel', $id_hotel)
            ->orderBy('rooms.name')
            ->get();
//        dd($list_room);

        if ($i != 0) {
            if ($cart[$i]['id_cate'] != $cart[$i - 1]['id_cate']) {
                $reset = 0;
            }
        }

        if ($reset == count($list_room)) {
            $cate = categoryRoom::query()->find($cart[$i]['id_cate']);
            set_fail($booking);
            if ($cate == null) {
                return [
                    "message" => 'Không tìm thấy loại phòng',
                    "status" => Response::HTTP_BAD_REQUEST
                ];
            }
            return [
                "message" => 'Đã hết phòng trong loại phòng ' . $cate->name,
                "status" => Response::HTTP_BAD_REQUEST
            ];
        }

        if (count($booking_d_record) >= 1) {
            if ($booking_d_record[count($booking_d_record) - 1]['id_cate'] == $cart[$i]['id_cate']) {
                $j++;
            }
        }

        if (empty($cart[$i]['services'])) {
            $booking_d_record[] = [
                'id_booking' => $booking->id,
                'id_room' => $list_room[$j]->id,
                'id_cate' => $cart[$i]['id_cate'],
                'id_services' => -1,
                'quantity_service' => null,
//                    'id_promotions' => $promotion,
            ];
            $reset++;
            continue;
        }

        foreach ($cart[$i]['services'] as $service) {
//            $quantity_service = $service['quantity'];
//            dd($service['id_service'] == -1);
////            if (){
////                dd($service['id_service']);
////                $quantity_service = -1;
////            }

            $booking_d_record[] = [
                'id_booking' => $booking->id,
                'id_room' => $list_room[$j]->id,
                'id_cate' => $cart[$i]['id_cate'],
                'id_services' => $service['id_service'],
                'quantity_service' => $service['quantity'],
            ];
            $reset++;
        }
    }

    bookingDetail::query()->insert($booking_d_record);

    set_success_booking($booking);
    Mail::to($data['email'])->send(new Sendmail());
    return [
        "message" => $booking,
        "status" => Response::HTTP_OK
    ];
}

function get_detail_booking($id) {
    $booking = booking::query()
        ->select('*')
        ->where('id', $id)
        ->first();

    if ($booking == null) {
        return [
            "message" => "Không tìm thấy đơn đặt hàng",
            "status" => Response::HTTP_BAD_REQUEST
        ];
    }

    $list_room = [];
    $total_service = 0;
    // GET ALL BOOKING OF user($id)
    $booking_d_record = bookingDetail::query()
        ->select('*')
        ->where('id_booking', $id)
        ->get();

    // GET ROOM IN BOOKING
    foreach ($booking_d_record as $value) {
        $room = room::query()
            ->select('rooms.id', 'rooms.name', 'category_rooms.id as id_category', 'category_rooms.name as category_name', 'category_rooms.image as category_image')
            ->join('hotel_categories', 'rooms.id_hotel_cate', '=', 'hotel_categories.id')
            ->join('category_rooms', 'category_rooms.id', '=', 'hotel_categories.id_cate')
            ->where('rooms.id', $value->id_room)
            ->first();
        if (in_array($room, $list_room)) {
            continue;
        }
        $list_room[] = $room;
    }

    // GET SERVICES IN ROOM
    foreach ($list_room as $list_room_key => $room) {

        $services = service::query()
            ->select('services.id', 'services.name', 'services.price', 'booking_details.quantity_service')
            ->join('booking_details', 'services.id', '=', 'booking_details.id_services')
            ->leftJoin('bookings', 'bookings.id', '=', 'booking_details.id_booking')
            ->where('booking_details.id_room', $room['id'])
            ->where('bookings.id', $booking->id)
            ->get();

        $list_room[$list_room_key]['services'] = $services;
        $total_service += count($services);
    }

    $booking['room'] = $list_room;
    $booking['total_room'] = count($list_room);
    $booking['total_service'] = $total_service;

    return [
        "message" => $booking,
        "status" => Response::HTTP_OK
    ];
}

function get_wallet_via_user($id_user) {
//    dd($id_user);
    return Wallet::query()
        ->select('wallets.*')
        ->join('users', 'users.id', '=', 'wallets.id_user')
        ->where('wallets.id_user', $id_user)
        ->first();
}

function sync_phone($phone) {
    $phone_len = strlen($phone);
    $result = null;
    if (Str::startsWith($phone, '0') && $phone_len == 10) {
        $result = '84' . substr($phone, 1);
    } elseif (Str::startsWith($phone, '+84') && $phone_len == 12) {
        $result = '84' . substr($phone, 3);
    } elseif (Str::startsWith($phone, '84') && $phone_len == 11) {
        $result = $phone;
    }
    return $result;
}

function check_quantity_voucher($id_voucher, $quantity) {
    $voucher = Voucher::query()->find($id_voucher);
    if ($voucher == null) {
        return false;
    }
    if ($voucher->quantity < $quantity) {
        return false;
    }
    return true;
}

function status_received_money($id_user, $status, $set=false) {
    $user = User::query()->find($id_user);
    $other_attributes = json_decode($user->other_attributes);
    if ($set) {
        $other_attributes->received_money = $status;
        $user->other_attributes = json_encode($other_attributes);
        $user->save();
    }
    return $other_attributes->received_money;
}

function topup_coin($id_user, $quantity_coin) {
    if (!isset($quantity_coin) || !is_int($quantity_coin) || $quantity_coin <= 0) {
        return response()->json([
            "error_message" => "Tham số coin không hợp lệ"
        ], Response::HTTP_BAD_REQUEST);
    }
    $wallet = get_wallet_via_user($id_user);
    $wallet->coin += $quantity_coin;
    $wallet->save();
    return $wallet;
}

function send_email() {

    # Instantiate the client.
        $mgClient = new Mailgun('ENTER_API_KEY_HERE');
        $domain = "michi.com";

    # Make the call to the client.
        $result = $mgClient->sendMessage("$domain",
            array('from'    => 'Mailgun Sandbox <postmaster@michi.com>',
                'to'      => 'Nguyen Nhat Thien <thiennnph25438@fpt.edu.vn>',
                'subject' => 'Hello Nguyen Nhat Thien',
                'template'    => 'test',
                'h:X-Mailgun-Variables'    => '{"test": "test"}'));
    # Send an email using your active template with the above snippet
    # You can see a record of this email in your logs: https://app.mailgun.com/app/logs.
}
