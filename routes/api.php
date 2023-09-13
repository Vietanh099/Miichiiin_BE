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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//

Route::prefix('admin')->group(function () {
    Route::resource('users', UserController::class)->except(['create', 'edit']);
    Route::prefix('users')->group(function () {
        Route::put('{id}/status', [UserController::class, 'updateState_user']);
    });
    Route::get('/statistical_user_month', [UserController::class, 'statistical_user_month']);
    Route::get('/statistical_user_year', [UserController::class, 'statistical_user_year']);

    // ADMIN
    Route::resource('admins', AdminController::class)->except(['create', 'edit']);
    Route::prefix('admins')->group(function () {
        Route::put('{id}/status', [AdminController::class, 'updateState_admin']);
    });
    // HOTEL
    Route::resource('hotel', hotelController::class);
    Route::prefix('hotel')->group(function () {
        Route::put('{id}/status', [hotelController::class, 'updateState_hotel']);
    });

    // ROOM
    Route::resource('room', roomsController::class);
    Route::prefix('room')->group(function () {
        Route::get('/cate_room/{id}', [roomsController::class, 'room_cate']);
        Route::put('{id}/status', [roomsController::class, 'updateState']);
    });
    // CATEGORY
    Route::resource('category', CateRoomController::class);
    Route::prefix('category')->group(function () {
        Route::put('{id}/status', [CateRoomController::class, 'updateState_cate']);
        Route::post('/find', [CateRoomController::class, 'find_of_name']);
    });
    // thong 'kee =>
    Route::get('/statistical', [CateRoomController::class, 'statistical']);
    Route::get('/statistical_year', [CateRoomController::class, 'statistical_year']);
    Route::get('/statistical_room_checkin/{check_in}/{check_out}', [CateRoomController::class, 'statistical_room_checkin']);

    Route::get('/statistical_total_amount', [CateRoomController::class, 'statistical_total_amount']);
    Route::get('/statistical_total_amount_month', [CateRoomController::class, 'statistical_total_amount_month']);



    Route::get('/statistical_cate', [CateRoomController::class, 'statistical_cate']);
    Route::get('/statistical_CateRoom_year', [CateRoomController::class, 'statistical_CateRoom_year']);
    Route::get('/statistical_cateRoom_checkin/{check_in}/{check_out}', [CateRoomController::class, 'statistical_cateRoom_checkin']);

    // CITY
    Route::resource('city', CityController::class);

    // DISTRICT
    Route::resource('district', districtController::class);
    Route::prefix('district')->group(function () {
        Route::put('{id}/status', [districtController::class, 'updateState_district']);
    });

    // BOOKING
    Route::resource('bookings', BookingController::class);
    Route::prefix('bookings')->group(function () {
        Route::put('{id}/status', [BookingController::class, 'updateState_booking']);
    });
    // BOOKING DETAIL
    Route::resource('bookingdetail', BookingDetailController::class);

    // IMAGE DETAIL
    Route::resource('imageDetail', ImageDetailController::class);

    // IMAGE
    Route::resource('image', ImageController::class);
    Route::resource('comfortDetail', ComfortDetailController::class);

    // SERVICE
    Route::resource('services', ServiceController::class)->except(['create', 'edit']);
    Route::prefix('services')->group(function () {
        Route::put('{id}/status', [ServiceController::class, 'updateState_services']);
    });
    Route::resource('service_detail', ServiceDetailController::class)->except(['create', 'edit']);

    // COMFORT
    Route::resource('comforts', ComfortController::class)->except(['create', 'edit']);
    Route::prefix('comforts')->group(function () {
        Route::put('{id}/status', [ComfortController::class, 'updateState_comfort']);
    });
    // RATE
    Route::resource('rates', RateController::class)->except(['create', 'edit']);
    Route::prefix('rates')->group(function () {
        Route::put('{id}/status', [RateController::class, 'updateState_rate']);
    });

    // PERMISSION
    Route::resource('permissions', PermissionController::class)->except(['create', 'edit']);
    Route::prefix('permissions')->group(function () {
        Route::put('{id}/status', [PermissionController::class, 'updateState_permission']);
    });
    Route::resource('permission_detail', PermissionDetailController::class)->except(['create', 'edit']);

    // VOUCHER
    Route::resource('vouchers', VoucherController::class)->except(['create', 'edit']);
    Route::prefix('vouchers')->group(function () {
        Route::put('{id}/status', [VoucherController::class, 'updateState_voucher']);
    });
    // ROLE
    Route::resource('roles', RoleControler::class)->except(['create', 'edit']);
    Route::prefix('roles')->group(function () {
        Route::put('{id}/status', [RoleControler::class, 'updateState_role']);
    });
    // BANNER
    Route::resource('banners', BannerController::class)->except(['create', 'edit']);
});
// USER


//Login
Route::post('login', [UserController::class, 'login'])->name('login');
Route::post('register', [UserController::class, 'register'])->name('register');
Route::post('logout', [UserController::class, 'logout'])->name('logout');
Route::post('loginGoogle', [UserController::class, 'loginGoogle'])->name('loginGoogle');
Route::post('loginFacebook', [UserController::class, 'loginFacebook'])->name('loginFacebook');

//
Route::middleware('auth:api')->prefix('users')->group(function () {
    // Các routes cần xác thực token
    Route::get('hotel', [hotelController::class, 'home_user']);
    Route::get('/hotel/city={id}', [hotelController::class, 'home_city']);
    // ...
});

// hiển thị thông tin hotel trang home_user
// hiển thị khách sạn theo thành phố
// hiển thị tất cả services cả hệ thống
// hiển thị khách sạn theo id (detail_hotel)
Route::get('/hotel/{id}', [hotelController::class, 'detail_hotel_user']);

Route::get('/services', [ServiceController::class, 'index']);
// hiển thị services theo id_hotel
Route::get('/services/hotels={id}', [ServiceController::class, 'list_services_hotel']);
// hiển thị comment theo id_cate
Route::get('/comment/id_cate={id}', [RateController::class, 'comment_cate']);
// hiển thị cate_room theo id
Route::get('/cateRoom/{id}', [CateRoomController::class, 'detail_list_cate']);
// hiển thị cate_room theo hotel
Route::get(
    '/listRoom/hotels={id}/{check_in?}/{check_out?}/{number_people?}/{total_room?}',
    [CateRoomController::class, 'list_cate']
);

Route::post(
    '/find',
    [CateRoomController::class, 'find']
);
// hiển thị booking theo id_user
Route::get('/booking/{id}', [BookingController::class, 'booking_list']);
// hiển thị booking_detail theo id_user
Route::get('/bookingDetail/{id}', [BookingDetailController::class, 'booking_detail_list']);

// hiển thị comfort theo loại phòng
Route::get('/comfort/cate={id}', [ComfortController::class, 'comfort_cate']);
// hiển thị voucher

Route::post('/store_image_cate/{id}', [hotelController::class, 'store_image_cate']);

Route::get('/voucher', [VoucherController::class, 'list_vourcher']);

