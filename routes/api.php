<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\ComfortController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PermissionDetailController;
use App\Http\Controllers\Admin\RateController;
use App\Http\Controllers\Admin\RoleControler;
use App\Http\Controllers\Admin\ServiceDetailController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\BookingDetailController;
use App\Http\Controllers\Admin\hotelController;
use App\Http\Controllers\Admin\roomsController;
use App\Http\Controllers\Admin\CateRoomController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\FloorController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\ImageDetailController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\districtController;
use App\Http\Controllers\Admin\ComfortDetailController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Admin\WalletController;
use App\Http\Controllers\BookingUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('auth/admin/login', [AdminController::class, 'login']);
Route::post('assign_permission', [RoleControler::class, 'assign_permission']);
Route::middleware('admin')->prefix('admin')->group(function () {
    // BANNER
    Route::middleware('permission:get banner,admins')->group(function () {
        Route::get('banners', [BannerController::class, 'index']);
        Route::prefix('banner')->group(function () {
            Route::get('/{id}', [BannerController::class, 'show']);
            Route::middleware('permission:add banner,admins')->post('/', [BannerController::class, 'store']);
            Route::middleware('permission:update banner,admins')->post('/{id}', [BannerController::class, 'update']);
            Route::middleware('permission:delete banner,admins')->delete('/{id}', [BannerController::class, 'destroy']);
        });
    });

    // VOUCHER
    Route::middleware('permission:get voucher,admins')->group(function () {
        Route::get('vouchers', [VoucherController::class, 'index']);
        Route::prefix('voucher')->group(function () {
            Route::get('/{id}', [VoucherController::class, 'show']);
            Route::middleware('permission:add voucher,admins')->post('/', [VoucherController::class, 'store']);
            Route::middleware('permission:update voucher,admins')->post('/{id}', [VoucherController::class, 'update']);
            Route::middleware('permission:delete voucher,admins')->delete('/{id}', [VoucherController::class, 'destroy']);
            Route::middleware('permission:update voucher,admins')->put('{id}/status', [VoucherController::class, 'updateState_voucher']);
        });
    });

    // HOTEL
    Route::middleware('permission:get hotel,admins')->group(function () {
        Route::get('hotels', [hotelController::class, 'index']);
        Route::prefix('hotel')->group(function () {
            Route::get('/{id}', [hotelController::class, 'show']);
            Route::middleware('permission:add hotel,admins')->post('/', [hotelController::class, 'store']);
            Route::middleware('permission:update hotel,admins')->post('/{id}', [hotelController::class, 'update']);
            Route::middleware('permission:delete hotel,admins')->delete('/{id}', [hotelController::class, 'destroy']);
            Route::middleware('permission:update hotel,admins')->put('{id}/status', [hotelController::class, 'updateState_hotel']);
        });
    });

    // ROOM
    Route::middleware('permission:get room,admins')->group(function () {
        Route::get('rooms/{check_in?}/{check_out?}', [roomsController::class, 'index']);
        Route::prefix('room')->group(function () {
            Route::get('/{id}', [roomsController::class, 'show']);
            Route::middleware('permission:add room,admins')->post('/', [roomsController::class, 'store']);
            Route::middleware('permission:update room,admins')->post('/{id}', [roomsController::class, 'update']);
            Route::middleware('permission:delete room,admins')->delete('/{id}', [roomsController::class, 'destroy']);
            Route::get('/cate_room/{id}', [roomsController::class, 'room_cate']);
            Route::middleware('permission:update room,admins')->put('{id}/status', [roomsController::class, 'updateState']);
        });
    });

    // CATEGORY
    Route::middleware('permission:get category,admins')->group(function () {
        Route::get('categories', [CateRoomController::class, 'index']);
        Route::prefix('category')->group(function () {
            Route::get('/{id}', [CateRoomController::class, 'show']);
            Route::middleware('permission:add category,admins')->post('', [CateRoomController::class, 'store']);
            Route::middleware('permission:update category,admins')->post('/{id}', [CateRoomController::class, 'update']);
            Route::middleware('permission:delete category,admins')->delete('/{id}', [CateRoomController::class, 'destroy']);

            Route::middleware('permission:update category,admins')->put('{id}/status', [CateRoomController::class, 'updateState_cate']);
        });
    });

    // SERVICE
    Route::middleware('permission:get service,admins')->group(function () {
        Route::get('services', [ServiceController::class, 'index']);
        Route::prefix('service')->group(function () {
            Route::get('/{id}', [ServiceController::class, 'show']);
            Route::middleware('permission:add service,admins')->post('/', [ServiceController::class, 'store']);
            Route::middleware('permission:update service,admins')->post('/{id}', [ServiceController::class, 'update']);
            Route::middleware('permission:delete service,admins')->delete('/{id}', [ServiceController::class, 'destroy']);

            Route::middleware('permission:update service,admins')->put('{id}/status', [ServiceController::class, 'updateState_services']);
        });
    });

    Route::resource('service_detail', ServiceDetailController::class)->except(['create', 'edit']);

    // COMFORT
    Route::middleware('permission:get comfort,admins')->group(function () {
        Route::get('comforts', [ComfortController::class, 'index']);
        Route::prefix('comfort')->group(function () {
            Route::get('/{id}', [ComfortController::class, 'show']);
            Route::middleware('permission:add comfort,admins')->post('/', [ComfortController::class, 'store']);
            Route::middleware('permission:update comfort,admins')->put('/{id}', [ComfortController::class, 'update']);
            Route::middleware('permission:delete comfort,admins')->delete('/{id}', [ComfortController::class, 'destroy']);

            Route::middleware('permission:update comfort,admins')->put('{id}/status', [ComfortController::class, 'updateState_comfort']);
        });
    });

    // RATE
    Route::middleware('permission:get rate,admins')->group(function () {
        Route::get('rates', [RateController::class, 'index']);
        Route::prefix('rate')->group(function () {
            Route::get('/{id}', [RateController::class, 'show']);
            Route::middleware('permission:update rate,admins')->put('/{id}', [RateController::class, 'update']);
            Route::middleware('permission:delete rate,admins')->delete('/{id}', [RateController::class, 'destroy']);

            Route::middleware('permission:update rate,admins')->put('{id}/status', [RateController::class, 'updateState_rate']);
        });
    });

    // ADMIN
    Route::middleware('permission:get admin,admins')->group(function () {
        Route::get('admins', [AdminController::class, 'index']);
        Route::prefix('admin')->group(function () {
            Route::get('/{id}', [AdminController::class, 'show']);
            Route::middleware('permission:add admin,admins')->post('/', [AdminController::class, 'store']);
            Route::middleware('permission:update admin,admins')->post('/{id}', [AdminController::class, 'update']);
            Route::middleware('permission:delete admin,admins')->delete('/{id}', [AdminController::class, 'destroy']);

            Route::middleware('permission:update admin,admins')->put('{id}/status', [AdminController::class, 'updateState_admin']);
        });
    });

    // PHÂN QUYỀN SAU
    // thong 'kee =>
    // thống kê số lượng phòng được đặt của cả hệ thống từ trước đến nay
    // Route::get('/statistical/{year}', [CateRoomController::class, 'statistical']);
    // thống kê  số lượng phòng được đặt trong  start_year và endYear
    // Route::get('/statistical_year/{startYear}/{endYear}', [CateRoomController::class, 'statistical_year']);
    // Route::get('/statistical_room_checkin/{check_in}/{check_out}', [CateRoomController::class, 'statistical_room_checkin']);
    //thống kê booking đặt trong 10 năm trở lại đây của car he thong


    // thống kê booking và dịch vụ của tất cả khách sạn theo tháng cả năm truyền vào

    // thống kê  booking và tổng doanh thu của  khách sạn trong  tháng cả năm truyền vào
    Route::middleware('permission:get statictical hotel,admins')->get('/statictical_total_booking_month_in_hotel/{id_hotels}/{year}', [CateRoomController::class, 'statictical_total_booking_month_in_hotel']);
    Route::middleware('permission:get statictical hotel,admins')->get('/statistical_rates_in_hotel/{id_hotel}/{month}/{year}', [CateRoomController::class, 'statistical_rates']);
    Route::middleware('permission:get statictical hotel,admins')->get('statistical_services/{month}/{year}/{id_hotel}', [CateRoomController::class, 'statistical_services']);

    // tổng doanh thu của cả hệ thống từ trước tới nay
    // Route::get('/statistical_total_amount', [CateRoomController::class, 'statistical_total_amount']);

    // Route::get('/statistical_total_amount_day/{id_hotel}/{month}/{year}', [CateRoomController::class, 'statistical_total_amount_day']);

    Route::middleware('permission:add statictical chain,admins')->get('/statictical_total_booking_monthl/{month}/{year}', [CateRoomController::class, 'statictical_total_booking_monthl']);
    Route::middleware('permission:add statictical chain,admins')->get('statistical_services_inchain/{month}/{year}', [CateRoomController::class, 'statistical_services_inchain']);
    Route::middleware('permission:add statictical chain,admins')->get('/statistical_rates/{month}/{year}/{category}', [CateRoomController::class, 'statistical_rates_inchain']);



    // thống kê loại phòng đặt trong 12 tháng của năm của tất cả
     //( có thể làm thêm tính riêng từng khách sạn và theo tháng)
    // Route::get('/statistical_cate_in_hotel/{year}', [CateRoomController::class, 'statistical_cate_in_hotel']);
       // thống kê  tổng doanh thu theo hotel trong năm 2023 của từng khách sạn // chỉ thằng chủ chuỗi

       // thống kê mỗi doanh thu riêng của khách sạn theo cả năm không phải tháng
       // có thể làm thêm tháng
    // Route::get('/statistical_total_amount_with_hotel/{year}', [CateRoomController::class, 'statistical_total_amount_with_hotel']);
// thống kê booking theo khoảng riêng
    // Route::get('/statictical_total_booking/{id_hotel}', [CateRoomController::class, 'statictical_total_booking']);
    // thống kê booking cả hệ thống 10 năm gần nhất
    // Route::get('statictical_total_booking_bettween_year_in_system', [CateRoomController::class, 'statictical_total_booking_bettween_year_in_system']);
    // thống kê loại phòng theo khoảng
    // Route::get('statistical_cateRoom_checkin/{check_in}/{check_out}', [CateRoomController::class, 'statistical_cateRoom_checkin']);


    // PHÂN QUYỀN SAU

    // USER
    Route::middleware('permission:get user,admins')->group(function () {
        Route::get('users', [UserController::class, 'index']);
        Route::prefix('user')->group(function () {
            Route::get('/{id}', [UserController::class, 'show']);
            Route::middleware('permission:add user,admins')->post('/', [UserController::class, 'store']);
            Route::middleware('permission:update user,admins')->post('/{id}', [UserController::class, 'update']);
            Route::middleware('permission:delete user,admins')->delete('/{id}', [UserController::class, 'destroy']);
            Route::middleware('permission:update user,admins')->put('{id}/status', [UserController::class, 'updateState_user']);
            Route::get('/statistical_user_month', [UserController::class, 'statistical_user_month']);
            Route::get('/statistical_user_year', [UserController::class, 'statistical_user_year']);
        });
    });

    // BOOKING
    Route::middleware('permission:get booking,admins')->group(function () {
        Route::get('bookings', [BookingController::class, 'index']);
        Route::prefix('booking')->group(function () {
            Route::get('/{id}', [BookingController::class, 'show']);
            Route::middleware('permission:add booking,admins')->post('/', [BookingController::class, 'store']);
            Route::middleware('permission:update booking,admins')->put('/{id}', [BookingController::class, 'update']);
            Route::middleware('permission:delete booking,admins')->delete('/{id}', [BookingController::class, 'destroy']);
            Route::middleware('permission:update booking,admins')->put('{id}/status', [BookingController::class, 'updateState_booking']);
        });
    });

    // BOOKING DETAIL
    Route::resource('bookingdetail', BookingDetailController::class);

    // IMAGE DETAIL
    Route::resource('imageDetail', ImageDetailController::class);

    // IMAGE
    Route::middleware('permission:get image,admins')->group(function () {
        Route::get('images', [ImageController::class, 'index']);
        Route::prefix('image')->group(function () {
            Route::get('/{id}', [ImageController::class, 'show']);
            Route::middleware('permission:add image,admins')->post('/', [ImageController::class, 'store']);
            Route::middleware('permission:update image,admins')->post('/{id}', [ImageController::class, 'update']);
            Route::middleware('permission:delete image,admins')->delete('/{id}', [ImageController::class, 'destroy']);
        });
    });

    Route::resource('comfortDetail', ComfortDetailController::class);

    // PERMISSION

    Route::middleware('permission:get permission,admins')->group(function () {
        Route::get('permissions', [PermissionController::class, 'index']);
        Route::prefix('permission')->group(function () {
            Route::get('/{id}', [PermissionController::class, 'show']);
            Route::middleware('permission:add permission,admins')->post('/', [PermissionController::class, 'store']);
            Route::middleware('permission:update permission,admins')->put('/{id}', [PermissionController::class, 'update']);
            Route::middleware('permission:delete permission,admins')->delete('/{id}', [PermissionController::class, 'destroy']);
            Route::middleware('permission:update permission,admins')->put('{id}/status', [PermissionController::class, 'updateState_permission']);
        });
    });

    Route::resource('permission_detail', PermissionDetailController::class)->except(['create', 'edit']);

    // ROLE
    Route::middleware('permission:get role,admins')->group(function () {
        Route::get('roles', [RoleControler::class, 'index']);
        Route::prefix('role')->group(function () {
            Route::get('/{id}', [RoleControler::class, 'show']);
            Route::middleware('permission:add role,admins')->post('/', [RoleControler::class, 'store']);
            Route::middleware('permission:update role,admins')->put('/{id}', [RoleControler::class, 'update']);
            Route::middleware('permission:delete role,admins')->delete('/{id}', [RoleControler::class, 'destroy']);
            Route::middleware('permission:update role,admins')->put('{id}/status', [RoleControler::class, 'updateState_role']);
        });
    });
});
// USER
// CITY
Route::resource('city', CityController::class);

// DISTRICT
Route::resource('district', districtController::class);
Route::prefix('district')->group(function () {
    Route::put('{id}/status', [districtController::class, 'updateState_district']);
});
Route::post('create_booking', [BookingUserController::class, 'create_booking']);
//Login
Route::post('login', [UserController::class, 'login'])->name('login');
Route::post('views/{id}', [CateRoomController::class, 'views']);
Route::post('likes/{id}', [CateRoomController::class, 'likes']);

Route::post('register', [UserController::class, 'register'])->name('register');
Route::post('logout', [UserController::class, 'logout'])->name('logout');
Route::post('loginGoogle', [UserController::class, 'loginGoogle'])->name('loginGoogle');
Route::post('loginFacebook', [UserController::class, 'loginFacebook'])->name('loginFacebook');

//
Route::middleware('auth:api')->prefix('users')->group(function () {
    // Các routes cần xác thực token

    // ...
});
Route::get('/comment/id_cate={id}', [RateController::class, 'comment_cate']);
Route::get('/comment/id_hotel={id}', [RateController::class, 'comment_hotel']);
Route::get('hotel', [hotelController::class, 'home_user']);
Route::get('/hotel/city={id}', [hotelController::class, 'home_city']);
// hiển thị thông tin hotel trang home_user
// hiển thị khách sạn theo thành phố
// hiển thị tất cả services cả hệ thống

// hiển thị khách sạn theo id (detail_hotel)
Route::get('/hotel/{id}', [hotelController::class, 'detail_hotel_user']);

Route::get('/services', [ServiceController::class, 'index']);
// hiển thị services theo id_hotel
Route::get('/services/hotels={id}', [ServiceController::class, 'list_services_hotel']);
// hiển thị comment theo id_cate
Route::get('/cateRoom/hotel={id}', [CateRoomController::class, 'detail_list_cate_inhotel']);

// hiển thị cate_room theo id
Route::get('/cateRoom/{id}/hotel={id_hotel}', [CateRoomController::class, 'detail_list_cate']);
// hiển thị cate_room theo hotel
Route::get(
    '/listRoom/hotels={id}/{check_in?}/{check_out?}/{number_people?}/{total_room?}',
    [CateRoomController::class, 'list_cate']
);
Route::get(
    '/find/hotels={id}/{check_in?}/{check_out?}/{number_people?}/{total_room?}',
    [CateRoomController::class, 'find']
);
// hiển thị booking theo id_user
Route::get('profile/{id_user}/booking/', [BookingUserController::class, 'list_history_booking']);
Route::get('profile/{id_user}/booking/{id_booking}', [BookingUserController::class, 'detaill_history_booking']);
Route::post('user/find_booking', [BookingUserController::class, 'find_booking']);

// hiển thị booking_detail theo id_user
Route::get('/bookingDetail/{id}', [BookingDetailController::class, 'booking_detail_list']);

// hiển thị comfort theo loại phòng
Route::get('/comfort/cate={id}', [ComfortController::class, 'comfort_cate']);

// hiển thị voucher

Route::get('/store_image_cate/{id}', [hotelController::class, 'store_image_cate']);

Route::get('/voucher', [VoucherController::class, 'list_vourcher']);

Route::resource('permissions', PermissionController::class)->except(['create', 'edit']);

Route::post('/rate', [RateController::class, 'store']);
Route::get('/services/id_hotel={id}', [ServiceController::class, 'list_services_hotel']);

// Wallet
Route::post('/topup', [WalletController::class, 'topup_coin_api']);
Route::post('/wallet/add_voucher', [WalletController::class, 'add_voucher_to_wallet']);

// Get user with quantity booking
//Route::get('/user/booking', [UserController::class, 'user_with_type']);
Route::get('/profile/{id_user}/wallet', [UserController::class, 'list_voucher_of_user']);
