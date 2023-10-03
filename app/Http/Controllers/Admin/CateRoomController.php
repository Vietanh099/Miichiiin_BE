<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRoomRequest;
use App\Models\booking;
use App\Models\bookingDetail;
use App\Models\categoryRoom;
use App\Models\hotel;
use App\Models\image;
use App\Models\imageDetail;
use App\Models\room;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;


class CateRoomController extends Controller
{
    //
    public function index()
    {
        $categoryRoom = categoryRoom::all();
        foreach ($categoryRoom as $key => $listImage) {
            $image = image::select('images.image')
                ->leftJoin('image_details', 'images.id', '=', 'image_details.id_image')
                ->leftJoin('category_rooms', 'image_details.id_cate', '=', 'category_rooms.id')
                ->where('category_rooms.id', '=', $listImage->id)
                ->get();
            $categoryRoom[$key]['imageUrl'] = $image;
        }
        return response()->json($categoryRoom);
    }


    public function detail_list_cate($id)
    {
        $status = 1;
        $rooms = CategoryRoom::select(
            'category_rooms.id',
            'category_rooms.name',
            'category_rooms.description',
            'category_rooms.image',
            'category_rooms.short_description',
            'category_rooms.quantity_of_people',
            'category_rooms.price',
            'category_rooms.acreage',
            'category_rooms.floor',
            'category_rooms.likes',
            'category_rooms.views',
            'category_rooms.created_at',
            'category_rooms.updated_at',
            'hotels.name as nameHotel',
            DB::raw('COUNT(DISTINCT rooms.id) as total_rooms'),
            DB::raw('COUNT(DISTINCT comforts.id) as total_comfort'),
        )
            ->leftJoin('rooms', 'rooms.id_cate', '=', 'category_rooms.id')
            ->leftJoin('hotels', 'hotels.id', '=', 'rooms.id_hotel')
            ->leftJoin('comfort_details', 'comfort_details.id_cate_room', '=', 'category_rooms.id')
            ->leftJoin('comforts', 'comforts.id', '=', 'comfort_details.id_comfort')
            ->where('category_rooms.id', '=', $id)
            ->where('category_rooms.status', '=', $status)
            ->groupBy(
                'category_rooms.id',
                'category_rooms.name',
                'category_rooms.description',
                'category_rooms.image',
                'category_rooms.short_description',
                'category_rooms.quantity_of_people',
                'category_rooms.price',
                'category_rooms.acreage',
                'category_rooms.floor',
                'category_rooms.likes',
                'category_rooms.views',
                'category_rooms.created_at',
                'category_rooms.updated_at',
                'hotels.name'
            )
            ->get();
        foreach ($rooms as $key => $listImage) {
            $image = image::select('images.image')
                ->leftJoin('image_details', 'images.id', '=', 'image_details.id_image')
                ->leftJoin('category_rooms', 'image_details.id_cate', '=', 'category_rooms.id')
                ->where('category_rooms.id', '=', $listImage->id)
                ->get();
            $rooms[$key]['imageUrl'] = $image;
        }

        return response()->json($rooms);
    }
    public function list_cate($id, $check_in = null, $check_out = null, $number_people = null, $total_room = null)
    {
        $status = 1;
        $startDate = isset($check_in) ? Carbon::parse($check_in) : Carbon::now()->setTime(0, 0);
        $endDate = isset($check_out) ? Carbon::parse($check_out) : $startDate->copy()->addDays(3)->setTime(0, 0);
        $number_of_people = $number_people ?? 1;
        $number_room = $total_room ?? 1;


        // Lấy danh sách tất cả các loại phòng
        $list_category = CategoryRoom::select(
            'category_rooms.id',
            'category_rooms.name',
            'category_rooms.description',
            'category_rooms.image',
            'category_rooms.short_description',
            'category_rooms.quantity_of_people',
            'category_rooms.price',
            'category_rooms.acreage',
            'category_rooms.floor',
            'category_rooms.likes',
            'category_rooms.views',
            'category_rooms.created_at',
            'category_rooms.updated_at',
            'hotels.name as nameHotel',
            DB::raw('COUNT(DISTINCT comforts.id) as Total_comfort')
        )
            ->leftJoin('rooms', 'rooms.id_cate', '=', 'category_rooms.id')
            ->leftJoin('hotels', 'hotels.id', '=', 'rooms.id_hotel')
            ->leftJoin('comfort_details', 'comfort_details.id_cate_room', '=', 'category_rooms.id')
            ->leftJoin('comforts', 'comforts.id', '=', 'comfort_details.id_comfort')
            ->leftJoin('booking_details', 'booking_details.id_room', '=', 'rooms.id')
            ->leftJoin('bookings', 'bookings.id', '=', 'booking_details.id_booking')
            ->where('hotels.id', '=', $id)
            ->where('category_rooms.status', '=', $status)
            ->groupBy(
                'category_rooms.id',
                'category_rooms.name',
                'category_rooms.image',
                'category_rooms.description',
                'category_rooms.short_description',
                'category_rooms.quantity_of_people',
                'category_rooms.price',
                'category_rooms.acreage',
                'category_rooms.floor',
                'category_rooms.likes',
                'category_rooms.views',
                'category_rooms.created_at',
                'category_rooms.updated_at',
                'hotels.name'
            )
            ->having('category_rooms.quantity_of_people', '>=', $number_of_people)
            ->get();
        // Lặp qua từng loại phòng
        foreach ($list_category as $category) {
            $categoryId = $category->id;

            // Lấy danh sách các phòng thuộc loại phòng hiện tại
            $rooms = Room::where('id_cate', $categoryId)
                ->where('id_hotel', $id)
                ->get();

            $bookedRooms = BookingDetail::whereHas('bookings', function ($query) use ($startDate, $endDate) {
                $query->where(function ($query) use ($startDate, $endDate) {
                    $query->where('check_in', '>=', $startDate)

                        ->where('check_out', '<=', $endDate)
                        ->whereNotIn('bookings.status', [2, 3]);
                })
                ->orWhereNull('booking_details.id_room');
            })

                ->where('id_room', '!=', null)
                ->where('id_cate', $categoryId)
                ->distinct('id_room') // Chỉ tính các phòng duy nhất
                ->count();

            $availableRoomCount = count($rooms) - $bookedRooms;

            $category->total_rooms_available = $availableRoomCount;
        }
        foreach ($list_category as $key => $listImage) {
            $image = Image::select('images.image')
                ->leftJoin('image_details', 'images.id', '=', 'image_details.id_image')
                ->leftJoin('category_rooms', 'image_details.id_cate', '=', 'category_rooms.id')
                ->where('category_rooms.id', '=', $listImage->id)
                ->get();
            $list_category[$key]['imageUrl'] = $image;
        }
        // Hiển thị kết quả
        return response()->json($list_category);
    }


    public function find($id, $check_in = null, $check_out = null, $number_people = null, $total_room = null)
    {
        $status = 1;
        $status = 1;
        $startDate = isset($check_in) ? Carbon::parse($check_in) : Carbon::now()->setTime(0, 0);
        $endDate = isset($check_out) ? Carbon::parse($check_out) : $startDate->copy()->addDays(3)->setTime(0, 0);
        $number_of_people = $number_people ?? 1;
        $number_room = $total_room ?? 1;

        $rooms = CategoryRoom::select(
            'category_rooms.id',
            'category_rooms.name',
            'category_rooms.description',
            'category_rooms.image',
            'category_rooms.short_description',
            'category_rooms.quantity_of_people',
            'category_rooms.price',
            'category_rooms.acreage',
            'category_rooms.floor',
            'category_rooms.likes',
            'category_rooms.views',
            'category_rooms.created_at',
            'category_rooms.updated_at',
            'hotels.name as nameHotel',
            DB::raw('COUNT(DISTINCT rooms.id) as Total_rooms'),
            DB::raw('COUNT(DISTINCT comforts.id) as Total_comfort')
        )
            ->leftJoin('rooms', 'rooms.id_cate', '=', 'category_rooms.id')
            ->leftJoin('hotels', 'hotels.id', '=', 'rooms.id_hotel')
            ->leftJoin('comfort_details', 'comfort_details.id_cate_room', '=', 'category_rooms.id')
            ->leftJoin('comforts', 'comforts.id', '=', 'comfort_details.id_comfort')
            ->leftJoin('booking_details', 'booking_details.id_room', '=', 'rooms.id')
            ->leftJoin('bookings', 'bookings.id', '=', 'booking_details.id_booking')
            ->where('hotels.id', '=', $id)
            ->where('category_rooms.status', '=', $status)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->where('bookings.check_in', '>=', $endDate)
                        ->orWhere('bookings.check_out', '<=', $startDate)
                        ->whereNotIn('bookings.status', [2, 3]);
                })
                    ->orWhereNull('booking_details.id_room');
            })
            ->groupBy(
                'category_rooms.id',
                'category_rooms.name',
                'category_rooms.image',
                'category_rooms.description',
                'category_rooms.short_description',
                'category_rooms.quantity_of_people',
                'category_rooms.price',
                'category_rooms.acreage',
                'category_rooms.floor',
                'category_rooms.likes',
                'category_rooms.views',
                'category_rooms.created_at',
                'category_rooms.updated_at',
                'hotels.name'
            )
            ->having('category_rooms.quantity_of_people', '>=', $number_of_people)
            ->having('Total_rooms', '>=', $number_room)
            ->orderBy('category_rooms.quantity_of_people')->get();

        foreach ($rooms as $key => $listImage) {
            $image = Image::select('images.image')
                ->leftJoin('image_details', 'images.id', '=', 'image_details.id_image')
                ->leftJoin('category_rooms', 'image_details.id_cate', '=', 'category_rooms.id')
                ->where('category_rooms.id', '=', $listImage->id)
                ->get();
            $rooms[$key]['imageUrl'] = $image;
        }

        return response()->json($rooms);
    }

    public function show($id)
    {
        $categoryRoom = categoryRoom::find($id);
        return response()->json($categoryRoom);
    }
    public function store(CategoryRoomRequest $request)
    {
        // nếu như tồn tại file sẽ upload file
        $params = $request->except('_token');
        $uploadedImage = Cloudinary::upload($params['image']->getRealPath());
        $params['image'] = $uploadedImage->getSecurePath();
        $categoryRoom  = categoryRoom::create($params);
        if ($categoryRoom->id) {
            return response()->json([
                'message' => $categoryRoom,
                'status' => 200
            ]);
        }
    }
    public function store_image_cate(CategoryRoomRequest $request, $id)
    {
        $params = $request->except('_token');
        $cate = hotel::find($id);
        if ($cate) {
            foreach ($request->file('images') as $image) {
                // Tải lên ảnh mới
                $uploadedImage = Cloudinary::upload($image->getRealPath());

                // Tạo bản ghi mới trong bảng `images`
                $imageRecord = new Image();
                $imageRecord->image = $uploadedImage->getSecurePath();
                $imageRecord->save();
                // Lưu thông tin hình ảnh vào bảng `image_details`
                $imageDetail = new imageDetail();
                $imageDetail->id_cate = $cate->id;
                $imageDetail->id_image = $imageRecord->id;
                $imageDetail->alt = 'Alt text for the image'; // Thay thế bằng alt text thích hợp
                $imageDetail->save();
            }
            return response()->json([
                'status' => "Add Ảnh Thành Công"
            ]);
        }
    }
    public function create()
    {
    }
    public function update(CategoryRoomRequest $request, $id)
    {
        $categoryRoom = categoryRoom::find($id);
        $params = $request->except('_token');
        $oldImg = $params['image'];

        if ($request->hasFile('image') && $request->file('image')) {
            if ($oldImg) {
                Cloudinary::destroy($oldImg);
            }
            $uploadedImage = Cloudinary::upload($request->image->getRealPath());
            $params['image'] = $uploadedImage->getSecurePath();
        }
        if ($categoryRoom) {
            $categoryRoom->update($params);
            return response()->json([
                'message' => $categoryRoom,
                'status' => "Sửa Thành Công"
            ]);
        }
    }
    public function edit(CategoryRoomRequest $request, $id)
    {
        $categoryRoom = categoryRoom::find($id);
        $params = $request->except('_token');
        if ($categoryRoom) {
            return response()->json([
                'message' => $categoryRoom,
            ]);
        }
    }

    public function destroy($id)
    {
        $categoryRoom = categoryRoom::find($id);
        if ($categoryRoom) {
            $oldImg = $categoryRoom->image;
            if ($oldImg) {
                Cloudinary::destroy($oldImg);
            }
            $categoryRoom->delete();
            return response()->json([
                'message' => "Delete success",
                'status' => 200
            ]);
        }
        return response()->json($categoryRoom);
    }
    public function updateState_cate(CategoryRoomRequest $request, $id)
    {
        $locked = $request->input('status');
        // Perform the necessary logic to lock or unlock based on the $locked state
        $categoryRoom = categoryRoom::find($id);
        if ($categoryRoom) {
            $categoryRoom->status = $locked == 1 ? 1 : 0;
            $categoryRoom->save();
            return response()->json([
                'message' => 'Toggle switch state updated successfully',
                'categoryRoom' => $categoryRoom,
            ]);
        }
        return response()->json([
            'message' => 'categoryRoom not found',
        ], 404);
    }
    // thống kê phòng bỏ
    public function statistical()
    {
        // thống kê trong từng tháng các phòng được đặt số lần alf

        //     $roomCountsByMonth = DB::table('bookings')
        //     ->join('booking_details', 'bookings.id', '=', 'booking_details.id_booking')
        //     ->select(DB::raw('MONTH(bookings.check_in) as month'), 'booking_details.id_room', DB::raw('COUNT(DISTINCT booking_details.id_booking) as total'))
        //     ->groupBy('month', 'booking_details.id_room')
        //     ->orderBy('month')
        //     ->get();

        // $countsByMonth = [];
        // foreach ($roomCountsByMonth as $roomCount) {
        //     $month = $roomCount->month;
        //     $roomId = $roomCount->id_room;
        //     $total = $roomCount->total;

        //     if (!isset($countsByMonth[$month])) {
        //         $countsByMonth[$month] = [];
        //     }

        //     $countsByMonth[$month][$roomId] = $total;
        // }
        $year = 2023;
        $bookings = DB::table('bookings')
            ->whereYear('check_in', $year)
            ->orWhereYear('check_out', $year)
            ->get();

        $roomCountsByMonth = array_fill(1, 12, 0);
        $total = 0;
        $roomCounts = []; // Khai báo biến $roomCounts

        foreach ($bookings as $booking) {
            $uniqueRoomIds = []; // Di chuyển lên đây để làm mới trong mỗi booking
            $roomCountForBooking = 0;

            $bookingId = $booking->id;
            $details = DB::table('booking_details')
                ->where('id_booking', $bookingId)
                ->get();

            foreach ($details as $detail) {
                $roomId = $detail->id_room;
                if (!isset($roomCounts[$roomId])) {
                    $roomCounts[$roomId] = 0;
                }

                if (!in_array($roomId, $uniqueRoomIds)) {
                    $roomCounts[$roomId]++;
                    $uniqueRoomIds[] = $roomId;
                    $roomCountForBooking++;
                    $total++;
                }
            }

            $checkInMonth = date('n', strtotime($booking->check_in));
            $checkOutMonth = date('n', strtotime($booking->check_out));

            for ($month = $checkInMonth; $month <= $checkOutMonth; $month++) {
                $roomCountsByMonth[$month] += $roomCountForBooking;
            }
        }

        return response()->json([
            'total' => $total,
            'roomCounts' => $roomCounts,
            'roomCountsByMonth' => $roomCountsByMonth,
        ]);
    }

    // thống kê loại phòng đặt trong 12 tháng của năm của cả hệ thống
    public function statistical_cate()
    {
        $year = 2023;
        $bookings = DB::table('bookings')
            ->whereYear('check_in', $year)
            ->orWhereYear('check_out', $year)
            ->get();
            $roomCounts = []; // Khai báo biến $roomCounts

        $roomCountsByMonth = array_fill(1, 12, 0);
        $total = 0;
        foreach ($bookings as $booking) {
            $uniqueRoomIds = []; // Di chuyển lên đây để làm mới trong mỗi booking
            $roomCountForBooking = 0;

            $bookingId = $booking->id;
            $details = DB::table('booking_details')
                ->where('id_booking', $bookingId)
                ->get();

            foreach ($details as $detail) {
                $id_cate = $detail->id_cate;
                if (!isset($roomCounts[$id_cate])) {
                    $roomCounts[$id_cate] = 0;
                }

                if (!in_array($id_cate, $uniqueRoomIds)) {
                    $roomCounts[$id_cate]++;
                    $uniqueRoomIds[] = $id_cate;
                    $roomCountForBooking++;
                    $total++;
                }
            }

            $checkInMonth = date('n', strtotime($booking->check_in));
            $checkOutMonth = date('n', strtotime($booking->check_out));

            for ($month = $checkInMonth; $month <= $checkOutMonth; $month++) {
                $roomCountsByMonth[$month] += $roomCountForBooking;
            }
        }

        return response()->json([
            'total' => $total,
            'roomCounts' => $roomCounts,
            'roomCountsByMonth' => $roomCountsByMonth,
        ]);
    }
     // thống kê loại phòng đặt trong 12 tháng của năm của 1 khách sạn
     public function statistical_cate_in_hotel()
     {
         $year = 2023;
         $id_hotels = 10;
         $bookings = DB::table('bookings')
             ->whereYear('check_in', $year)
             ->orWhereYear('check_out', $year)
             ->get();
             $roomCounts = []; // Khai báo biến $roomCounts

         $roomCountsByMonth = array_fill(1, 12, 0);
         $total = 0;
         foreach ($bookings as $booking) {
             $uniqueRoomIds = []; // Di chuyển lên đây để làm mới trong mỗi booking
             $roomCountForBooking = 0;

             $bookingId = $booking->id;
             $details = DB::table('booking_details')
                 ->where('id_booking', $bookingId)
                 ->where('id_hotel', $id_hotels)
                 ->get();

             foreach ($details as $detail) {
                 $id_cate = $detail->id_cate;
                 if (!isset($roomCounts[$id_cate])) {
                     $roomCounts[$id_cate] = 0;
                 }

                 if (!in_array($id_cate, $uniqueRoomIds)) {
                     $roomCounts[$id_cate]++;
                     $uniqueRoomIds[] = $id_cate;
                     $roomCountForBooking++;
                     $total++;
                 }
             }

             $checkInMonth = date('n', strtotime($booking->check_in));
             $checkOutMonth = date('n', strtotime($booking->check_out));

             for ($month = $checkInMonth; $month <= $checkOutMonth; $month++) {
                 $roomCountsByMonth[$month] += $roomCountForBooking;
             }
         }

         return response()->json([
             'total' => $total,
             'roomCounts' => $roomCounts,
             'roomCountsByMonth' => $roomCountsByMonth,
         ]);
     }

    public function statistical_total_amount()
    {
        $year = 2023;

        $totalAmount = DB::table('bookings')
            ->whereYear('check_in', $year)
            ->sum('total_amount');

        return response()->json([
            'total_amount' => $totalAmount,
        ]);


    }

    // thống kê  tổng doanh thu theo hotel trong năm 2023 của từng khách sạn // chỉ thằng chủ chuỗi
    public function statistical_total_amount_with_hotel(){
        $year = 2023;

        $totalAmountByHotel = [];

        $bookings = DB::table('bookings')
            ->whereYear('check_in', $year)
            ->get();

        foreach ($bookings as $booking) {
            $bookingId = $booking->id;
            $details = DB::table('booking_details')
                ->where('id_booking', $bookingId)
                ->get();

            foreach ($details as $detail) {
                $roomId = $detail->id_room;
                $room = DB::table('rooms')
                    ->join('hotels', 'rooms.id_hotel', '=', 'hotels.id')
                    ->where('rooms.id', $roomId)
                    ->select('hotels.name')
                    ->first();

                if ($room) {
                    $hotelName = $room->name;

                    if (!isset($totalAmountByHotel[$hotelName])) {
                        $totalAmountByHotel[$hotelName] = 0;
                    }

                    $totalAmountByHotel[$hotelName] += $booking->total_amount;
                }
            }
        }

        return response()->json([
            'total_amount_by_hotel' => $totalAmountByHotel,
        ]);
    }


    // thống kê doanh thu theo khách sạn  từng tháng
    public function statistical_total_amount_with_hotel_moth(){
        $year = 2023;

        $totalAmountByHotel = [];

        $bookings = DB::table('bookings')
            ->whereYear('check_in', $year)
            ->get();

        foreach ($bookings as $booking) {
            $bookingId = $booking->id;
            $details = DB::table('booking_details')
                ->where('id_booking', $bookingId)
                ->get();

            foreach ($details as $detail) {
                $roomId = $detail->id_room;
                $room = DB::table('rooms')
                    ->join('hotels', 'rooms.id_hotel', '=', 'hotels.id')
                    ->where('rooms.id', $roomId)
                    ->select('hotels.name')
                    ->first();

                if ($room) {
                    $hotelName = $room->name;

                    if (!isset($totalAmountByHotel[$hotelName])) {
                        $totalAmountByHotel[$hotelName] = array_fill(1, 12, 0);
                    }

                    $month = date('n', strtotime($booking->check_in));
                    $totalAmountByHotel[$hotelName][$month] += $booking->total_amount;
                }
            }
        }

        return response()->json([
            'total_amount_by_hotel' => $totalAmountByHotel,
        ]);
    }
     // thống kê doanh thu theo khách sạn  từng tháng của 1 khách sạn
     public function statistical_total_amount_with_hotel_moth_id_hotel(){
        $year = 2023;

        $totalAmountByHotel = [];
        $id_hotelss = 10;
        $bookings = DB::table('bookings')
            ->whereYear('check_in', $year)
            ->get();

        foreach ($bookings as $booking) {
            $bookingId = $booking->id;
            $details = DB::table('booking_details')
                ->where('id_booking', $bookingId)
                ->get();

            foreach ($details as $detail) {
                $roomId = $detail->id_room;
                $room = DB::table('rooms')
                    ->join('hotels', 'rooms.id_hotel', '=', 'hotels.id')
                    ->where('rooms.id', $roomId)
                    ->where('rooms.id_hotel', $id_hotelss)
                    ->select('hotels.name')
                    ->first();

                if ($room) {
                    $hotelName = $room->name;

                    if (!isset($totalAmountByHotel[$hotelName])) {
                        $totalAmountByHotel[$hotelName] = array_fill(1, 12, 0);
                    }

                    $month = date('n', strtotime($booking->check_in));
                    $totalAmountByHotel[$hotelName][$month] += $booking->total_amount;
                }
            }
        }

        return response()->json([
            'total_amount_by_hotel' => $totalAmountByHotel,
        ]);
    }

    // thống kê doanh thu của từng khách sạn theo 10 năm trở lại đây
    public function statistical_total_amount_with_hotel_year(){
        $currentYear = date('Y');
        $yearRange = range($currentYear - 9, $currentYear);

        $totalAmountByHotel = [];

        $bookings = DB::table('bookings')
            ->whereIn(DB::raw('YEAR(check_in)'), $yearRange)
            ->get();

        foreach ($bookings as $booking) {
            $bookingId = $booking->id;
            $details = DB::table('booking_details')
                ->where('id_booking', $bookingId)
                ->get();

            foreach ($details as $detail) {
                $roomId = $detail->id_room;
                $room = DB::table('rooms')
                    ->join('hotels', 'rooms.id_hotel', '=', 'hotels.id')
                    ->where('rooms.id', $roomId)
                    ->select('hotels.name')
                    ->first();

                if ($room) {
                    $hotelName = $room->name;

                    if (!isset($totalAmountByHotel[$hotelName])) {
                        $totalAmountByHotel[$hotelName] = array_fill_keys($yearRange, array_fill(1, 12, 0));
                    }

                    $year = date('Y', strtotime($booking->check_in));
                    $month = date('n', strtotime($booking->check_in));
                    $totalAmountByHotel[$hotelName][$year][$month] += $booking->total_amount;
                }
            }
        }

        return response()->json([
            'total_amount_by_hotel' => $totalAmountByHotel,
        ]);
    }
      // thống kê doanh thu của từng khách sạn theo 10 năm trở lại đây cuar 1 khach san
      public function statistical_total_amount_with_hotel_year_id_hotel(){
        $currentYear = date('Y');
        $yearRange = range($currentYear - 9, $currentYear);
        $id_hotelsss = 10;
        $totalAmountByHotel = [];

        $bookings = DB::table('bookings')
            ->whereIn(DB::raw('YEAR(check_in)'), $yearRange)
            ->get();

        foreach ($bookings as $booking) {
            $bookingId = $booking->id;
            $details = DB::table('booking_details')
                ->where('id_booking', $bookingId)
                ->get();

            foreach ($details as $detail) {
                $roomId = $detail->id_room;
                $room = DB::table('rooms')
                    ->join('hotels', 'rooms.id_hotel', '=', 'hotels.id')
                    ->where('rooms.id', $roomId)
                    ->where('rooms.id_hotel', $id_hotelsss)
                    ->select('hotels.name')
                    ->first();

                if ($room) {
                    $hotelName = $room->name;

                    if (!isset($totalAmountByHotel[$hotelName])) {
                        $totalAmountByHotel[$hotelName] = array_fill_keys($yearRange, array_fill(1, 12, 0));
                    }

                    $year = date('Y', strtotime($booking->check_in));
                    $month = date('n', strtotime($booking->check_in));
                    $totalAmountByHotel[$hotelName][$year][$month] += $booking->total_amount;
                }
            }
        }

        return response()->json([
            'total_amount_by_hotel' => $totalAmountByHotel,
        ]);
    }

    // thống kê doanh thu của từng khách sạn theo khoảng thời gian check_in check_out
    public function statistical_total_amount_with_hotel_range(){
        $totalAmountByHotel = [];
        $checkIn = '2023-11-30';
        $checkOut = '2023-12-30';
        $checkInTime = strtotime($checkIn); // Thời gian check-in được truyền vào
        $checkOutTime = strtotime($checkOut); // Thời gian check-out được truyền vào

        $bookings = DB::table('bookings')
            ->where('check_in', '>=', date('Y-m-d H:i:s', $checkInTime))
            ->where('check_out', '<=', date('Y-m-d H:i:s', $checkOutTime))
            ->get();

        foreach ($bookings as $booking) {
            $bookingId = $booking->id;
            $details = DB::table('booking_details')
                ->where('id_booking', $bookingId)
                ->get();

            foreach ($details as $detail) {
                $roomId = $detail->id_room;
                $room = DB::table('rooms')
                    ->join('hotels', 'rooms.id_hotel', '=', 'hotels.id')
                    ->where('rooms.id', $roomId)
                    ->select('hotels.name')
                    ->first();

                if ($room) {
                    $hotelName = $room->name;

                    if (!isset($totalAmountByHotel[$hotelName])) {
                        $totalAmountByHotel[$hotelName] = 0;
                    }

                    $totalAmountByHotel[$hotelName] += $booking->total_amount;
                }
            }
        }

        return response()->json([
            'total_amount_by_hotel' => $totalAmountByHotel,
        ]);

    }
    // thống kê doanh thu của 1 khách sạn theo khoảng thời gian check_in check_our
    public function statictical_total_amount_with_hotel_id(){
        $totalAmountByHotel = [];
        $checkIn = '2023-09-30';
        $checkOut = '2023-12-30';
        $id_hotelssss = 10;
        $checkInTime = strtotime($checkIn); // Thời gian check-in được truyền vào
        $checkOutTime = strtotime($checkOut); // Thời gian check-out được truyền vào

        $bookings = DB::table('bookings')
            ->where('check_in', '>=', date('Y-m-d H:i:s', $checkInTime))
            ->where('check_out', '<=', date('Y-m-d H:i:s', $checkOutTime))
            ->get();

        foreach ($bookings as $booking) {
            $bookingId = $booking->id;
            $details = DB::table('booking_details')
                ->where('id_booking', $bookingId)
                ->get();

            foreach ($details as $detail) {
                $roomId = $detail->id_room;
                $room = DB::table('rooms')
                    ->join('hotels', 'rooms.id_hotel', '=', 'hotels.id')
                    ->where('rooms.id', $roomId)
                    ->where('rooms.id_hotel', $id_hotelssss)
                    ->select('hotels.name')
                    ->first();

                if ($room) {
                    $hotelName = $room->name;

                    if (!isset($totalAmountByHotel[$hotelName])) {
                        $totalAmountByHotel[$hotelName] = 0;
                    }

                    $totalAmountByHotel[$hotelName] += $booking->total_amount;
                }
            }
        }

        return response()->json([
            'total_amount_by_hotel' => $totalAmountByHotel,
        ]);
    }

    // thống kê tổng tiền theo tháng của cả hệ thống chỉ dành cho chủ hệ thống
    public function statistical_total_amount_month()
    {
        $year = 2023;
        $totalAmountByMonth = DB::table('bookings')
            ->selectRaw('MONTH(check_in) as month, SUM(total_amount) as total_amount')
            ->whereYear('check_in', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total_amount', 'month')
            ->toArray();

        return response()->json([
            'total_amount_by_month' => $totalAmountByMonth,
        ]);
    }

    /// thống kê booking trong khoảng thời gian  của cả hệ thống
    public function statictical_total_booking(){
        $checkInTime = '2023-10-01';
        $checkOutTime = '2023-12-10';

        $bookings = DB::table('bookings')
            ->join('booking_details', 'bookings.id', '=', 'booking_details.id_booking')
            ->join('rooms', 'booking_details.id_room', '=', 'rooms.id')
            ->join('hotels', 'rooms.id_hotel', '=', 'hotels.id')
            ->where('bookings.check_in', '>=', $checkInTime)
            ->where('bookings.check_out', '<=', $checkOutTime)
            ->get();

        $bookingCountsByHotel = [];

        foreach ($bookings as $booking) {
            $hotelName = $booking->name;

            if (!isset($bookingCountsByHotel[$hotelName])) {
                $bookingCountsByHotel[$hotelName] = [];
            }

            $checkInMonth = date('n', strtotime($booking->check_in));

            if (!isset($bookingCountsByHotel[$hotelName][$checkInMonth])) {
                $bookingCountsByHotel[$hotelName][$checkInMonth] = 0;
            }

            $bookingCountsByHotel[$hotelName][$checkInMonth]++;
        }

        return response()->json([
            'booking_counts_by_hotel' => $bookingCountsByHotel,
        ]);

    }
      /// thống kê booking trong khoảng thời gian  của 1 khách sạn
      public function statictical_total_booking_id_hotel(){
        $checkInTime = '2023-10-01';
        $checkOutTime = '2023-12-10';
        $id_hotels = 10;
        $bookings = DB::table('bookings')
            ->join('booking_details', 'bookings.id', '=', 'booking_details.id_booking')
            ->join('rooms', 'booking_details.id_room', '=', 'rooms.id')
            ->join('hotels', 'rooms.id_hotel', '=', 'hotels.id')
            ->where('bookings.check_in', '>=', $checkInTime)
            ->where('bookings.check_out', '<=', $checkOutTime)
            ->where('hotels.id', '=', $id_hotels)
            ->get();

        $bookingCountsByHotel = [];

        foreach ($bookings as $booking) {
            $hotelName = $booking->name;

            if (!isset($bookingCountsByHotel[$hotelName])) {
                $bookingCountsByHotel[$hotelName] = [];
            }

            $checkInMonth = date('n', strtotime($booking->check_in));

            if (!isset($bookingCountsByHotel[$hotelName][$checkInMonth])) {
                $bookingCountsByHotel[$hotelName][$checkInMonth] = 0;
            }

            $bookingCountsByHotel[$hotelName][$checkInMonth]++;
        }

        return response()->json([
            'booking_counts_by_hotel' => $bookingCountsByHotel,
        ]);

    }
    //  thống kê booking 12 tháng trong năm của 1 khách sạn
    public function statictical_total_booking_month_in_hotel(){
        $id_hotels = 10;
        $bookings = DB::table('bookings')
            ->join('booking_details', 'bookings.id', '=', 'booking_details.id_booking')
            ->join('rooms', 'booking_details.id_room', '=', 'rooms.id')
            ->join('hotels', 'rooms.id_hotel', '=', 'hotels.id')
            ->where('hotels.id', '=', $id_hotels)
            ->get();

        $months = range(1, 12);
        $bookingCountsByMonth = [];

        // Initialize month counts
        foreach ($months as $month) {
            $bookingCountsByMonth[$month] = 0;
        }

        foreach ($bookings as $booking) {
            $checkInMonth = date('n', strtotime($booking->check_in));
            $bookingCountsByMonth[$checkInMonth]++;
        }

        // Check and update months with no bookings
        foreach ($months as $month) {
            if (!isset($bookingCountsByMonth[$month])) {
                $bookingCountsByMonth[$month] = 0;
            }
        }

        return response()->json([
            'booking_counts_by_month' => $bookingCountsByMonth,
        ]);
    }

     //  thống kê booking 12 tháng trong năm của cả hệ thống
     public function statictical_total_booking_monthl(){
        $bookings = DB::table('bookings')
            ->join('booking_details', 'bookings.id', '=', 'booking_details.id_booking')
            ->join('rooms', 'booking_details.id_room', '=', 'rooms.id')
            ->join('hotels', 'rooms.id_hotel', '=', 'hotels.id')
            ->get();

        $months = range(1, 12);
        $bookingCountsByMonth = [];

        // Initialize month counts
        foreach ($months as $month) {
            $bookingCountsByMonth[$month] = 0;
        }

        foreach ($bookings as $booking) {
            $checkInMonth = date('n', strtotime($booking->check_in));
            $bookingCountsByMonth[$checkInMonth]++;
        }

        // Check and update months with no bookings
        foreach ($months as $month) {
            if (!isset($bookingCountsByMonth[$month])) {
                $bookingCountsByMonth[$month] = 0;
            }
        }

        return response()->json([
            'booking_counts_by_month' => $bookingCountsByMonth,
        ]);
    }
    // thống kê booking đặt trong 10 năm trở lại đây của 1 khác sạn

    public function statictical_total_booking_bettween_year(){
        $id_hotels = 10;
        $startDate = date('Y-m-d', strtotime('-10 years'));
        $endDate = date('Y-m-d');

        $bookings = DB::table('bookings')
        ->join('booking_details', 'bookings.id', '=', 'booking_details.id_booking')
        ->join('rooms', 'booking_details.id_room', '=', 'rooms.id')
        ->join('hotels', 'rooms.id_hotel', '=', 'hotels.id')
        ->where('hotels.id', '>=', $id_hotels)
        ->get();
        $bookingCountsByYear = [];
        $years = range(date('Y') - 10, date('Y'));

        foreach ($years as $year) {
            $bookingCountsByYear[$year] = 0;
        }

        foreach ($bookings as $booking) {
            $checkInYear = date('Y', strtotime($booking->check_in));
            $bookingCountsByYear[$checkInYear]++;
        }

        $bookingCounts = [];
        foreach ($bookingCountsByYear as $year => $count) {
            $bookingCounts[$year] = $count;
        }

        return response()->json([
            'booking_counts_by_year' => $bookingCounts,
        ]);
        }
  // thống kê booking đặt trong 10 năm trở lại đây của car he thong

  public function statictical_total_booking_bettween_year_in_system(){
$startDate = date('Y-m-d', strtotime('-10 years'));
$endDate = date('Y-m-d');

$bookings = DB::table('bookings')
->join('booking_details', 'bookings.id', '=', 'booking_details.id_booking')
->join('rooms', 'booking_details.id_room', '=', 'rooms.id')
->join('hotels', 'rooms.id_hotel', '=', 'hotels.id')
->whereBetween('bookings.check_in', [$startDate, $endDate])
->get();

$months = range(1, 12);
$years = range(date('Y') - 10, date('Y'));
$bookingCountsByMonth = [];

// Initialize month counts
foreach ($years as $year) {
foreach ($months as $month) {
    $bookingCountsByMonth[$year][$month] = 0;
}
}

foreach ($bookings as $booking) {
$checkInMonth = date('n', strtotime($booking->check_in));
$checkInYear = date('Y', strtotime($booking->check_in));
$bookingCountsByMonth[$checkInYear][$checkInMonth]++;
}

// Check and update months with no bookings
foreach ($years as $year) {
foreach ($months as $month) {
    if (!isset($bookingCountsByMonth[$year][$month])) {
        $bookingCountsByMonth[$year][$month] = 0;
    }
}
}

return response()->json([
'booking_counts_by_month' => $bookingCountsByMonth,
]);
}
    public function statistical_year()
    {

        // hiển thị từng phòng theo từng năm
        // $startYear = 2013;
        // $endYear = 2023;
        // $yearRange = range($startYear, $endYear);

        // $bookings = DB::table('bookings')
        //     ->whereIn(DB::raw('YEAR(check_in)'), $yearRange)
        //     ->orWhereIn(DB::raw('YEAR(check_out)'), $yearRange)
        //     ->get();

        // $roomCounts = [];
        // $total = 0;
        // foreach ($bookings as $booking) {
        //     $bookingId = $booking->id;
        //     $details = DB::table('booking_details')
        //         ->where('id_booking', $bookingId)
        //         ->get();

        //     foreach ($details as $detail) {
        //         $roomId = $detail->id_room;
        //         if (!isset($roomCounts[$roomId])) {
        //             $roomCounts[$roomId] = array_fill($startYear, $endYear - $startYear + 1, 0);
        //         }

        //         $year = date('Y', strtotime($booking->check_in));
        //         if ($year >= $startYear && $year <= $endYear) {
        //             $roomCounts[$roomId][$year]++;
        //             $total++;
        //         }
        //     }
        // }

        // $response = [
        //     'total' => $total,
        //     'roomCounts' => [],
        // ];

        // foreach ($roomCounts as $roomId => $counts) {
        //     $response['roomCounts'][$roomId] = array_slice($counts, 0, $endYear - $startYear + 1);
        // }

        // return response()->json($response);



        $startYear = 2013;
        $endYear = 2023;
        $yearRange = range($startYear, $endYear);
        $roomCounts = []; // Khai báo biến $roomCounts

        $bookings = DB::table('bookings')
            ->whereIn(DB::raw('YEAR(check_in)'), $yearRange)
            ->orWhereIn(DB::raw('YEAR(check_out)'), $yearRange)
            ->get();

        $roomCounts = array_fill($startYear, $endYear - $startYear + 1, 0);
        $total = 0;

        foreach ($bookings as $booking) {
            $bookingId = $booking->id;
            $details = DB::table('booking_details')
                ->where('id_booking', $bookingId)
                ->get();

            $bookedRooms = []; // Lưu trữ danh sách các phòng đã được đếm trong booking hiện tại

            foreach ($details as $detail) {
                $roomId = $detail->id_room;

                // Kiểm tra nếu phòng đã được đếm trước đó trong booking hiện tại thì bỏ qua
                if (in_array($roomId, $bookedRooms)) {
                    continue;
                }

                $year = date('Y', strtotime($booking->check_in));
                if ($year >= $startYear && $year <= $endYear) {
                    $roomCounts[$year]++;
                    $total++;
                }

                $bookedRooms[] = $roomId; // Thêm phòng vào danh sách đã đếm của booking hiện tại
            }
        }

        $response = [
            'total' => $total,
            'roomCounts' => $roomCounts,
        ];

        return response()->json($response);
    }

    public function statistical_CateRoom_year()
    {

        $startYear = 2013;
        $endYear = 2023;
        $yearRange = range($startYear, $endYear);
        $roomCounts = []; // Khai báo biến $roomCounts

        $bookings = DB::table('bookings')
            ->whereIn(DB::raw('YEAR(check_in)'), $yearRange)
            ->orWhereIn(DB::raw('YEAR(check_out)'), $yearRange)
            ->get();

        $roomCounts = array_fill($startYear, $endYear - $startYear + 1, 0);
        $total = 0;

        foreach ($bookings as $booking) {
            $bookingId = $booking->id;
            $details = DB::table('booking_details')
                ->where('id_booking', $bookingId)
                ->get();

            $bookedRooms = []; // Lưu trữ danh sách các phòng đã được đếm trong booking hiện tại

            foreach ($details as $detail) {
                $id_cate = $detail->id_cate;

                // Kiểm tra nếu phòng đã được đếm trước đó trong booking hiện tại thì bỏ qua
                if (in_array($id_cate, $bookedRooms)) {
                    continue;
                }

                $year = date('Y', strtotime($booking->check_in));
                if ($year >= $startYear && $year <= $endYear) {
                    $roomCounts[$year]++;
                    $total++;
                }

                $bookedRooms[] = $id_cate; // Thêm phòng vào danh sách đã đếm của booking hiện tại
            }
        }

        $response = [
            'total' => $total,
            'roomCounts' => $roomCounts,
        ];

        return response()->json($response);
    }
    public function statistical_room_checkin($check_in, $check_out)
    {
        $bookings = DB::table('bookings')
            ->whereBetween('check_in', [$check_in, $check_out])
            ->orWhereBetween('check_out', [$check_in, $check_out])
            ->get();

        $roomCounts = [];
        $total = 0;

        foreach ($bookings as $booking) {
            $uniqueRoomIds = []; // Di chuyển lên đây để làm mới trong mỗi booking

            $bookingId = $booking->id;
            $details = DB::table('booking_details')
                ->where('id_booking', $bookingId)
                ->get();

            foreach ($details as $detail) {
                $roomId = $detail->id_room;
                if (!isset($roomCounts[$roomId])) {
                    $roomCounts[$roomId] = 0;
                }

                if (!in_array($roomId, $uniqueRoomIds)) {
                    $roomCounts[$roomId]++;
                    $uniqueRoomIds[] = $roomId;
                    $total++;
                }
            }
        }

        return response()->json([
            'total' => $total,
            'roomCounts' => $roomCounts,
        ]);
    }
    public function statistical_cateRoom_checkin($check_in, $check_out)
    {
        $bookings = DB::table('bookings')
            ->whereBetween('check_in', [$check_in, $check_out])
            ->orWhereBetween('check_out', [$check_in, $check_out])
            ->get();

        $roomCounts = [];
        $total = 0;

        foreach ($bookings as $booking) {
            $uniqueRoomIds = []; // Di chuyển lên đây để làm mới trong mỗi booking

            $bookingId = $booking->id;
            $details = DB::table('booking_details')
                ->where('id_booking', $bookingId)
                ->get();

            foreach ($details as $detail) {
                $id_cate = $detail->id_cate;
                if (!isset($roomCounts[$id_cate])) {
                    $roomCounts[$id_cate] = 0;
                }

                if (!in_array($id_cate, $uniqueRoomIds)) {
                    $roomCounts[$id_cate]++;
                    $uniqueRoomIds[] = $id_cate;
                    $total++;
                }
            }
        }

        return response()->json([
            'total' => $total,
            'roomCounts' => $roomCounts,
        ]);
    }
}
