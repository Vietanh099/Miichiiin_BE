<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\Token;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderByDesc('created_at')->get();
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $user = new User();
        $user->fill($request->except(['re_password', '_token']));

        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }

        $uploadedImage = Cloudinary::upload($request->image->getRealPath());
        $user->image = $uploadedImage->getSecurePath();
        $user->save();

        return response()->json($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id)
    {
        $user = User::query()->find($id);
        $oldImg = $user->image;

        $user->fill($request->except(['re_password', '_token']));

        if ($request->file('image')) {
            if ($oldImg) {
                Cloudinary::destroy($oldImg);
            }
            $uploadedImage = Cloudinary::upload($request->image->getRealPath());
            $user->image = $uploadedImage->getSecurePath();
        }

        $user->save();

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::query()->find($id);
        $oldImg = $user->image;

        if($user){
            if ($oldImg) {
                Cloudinary::destroy($oldImg);
            }
        $user->delete();
        }
        return response()->json(Response::HTTP_OK);
    }
    public function login(UserRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('token')->accessToken;
                return response()->json(['token' => $token,
                'user' => $user], 200);
            }
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function register(UserRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $credentials['password'] = bcrypt($credentials['password']);

        $user = User::create($credentials);

        if ($user) {
            $token = $user->createToken('token')->accessToken;
            return response()->json(['token' => $token, 'user' => $user], 200);
        }

        return response()->json(['error' => 'Registration failed'], 500);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $user->tokens()->each(function (Token $token) {
            $token->revoke();
        return response()->json(['message' => 'Logged out successfully']);

        });
        return response()->json(['message' => 'Logged out Faild']);

    }

    public function updateState_user(UserRequest $request, $id)
    {
        $locked = $request->input('status');
        // Perform the necessary logic to lock or unlock based on the $locked state
        $User = User::find($id);
        if ($User) {
            $User->status = $locked == 1 ? 1 : 0;
            $User->save();
            return response()->json([
                'message' => 'Toggle switch state updated successfully',
                'User' => $User,
            ]);
        }
        return response()->json([
            'message' => 'Room not found',
        ], 404);
    }
    public function statistical_user_month()
    {
        $monthlyStatistics = [];

        for ($month = 1; $month <= 12; $month++) {
            $userCount = User::whereMonth('created_at', $month)->count();
            $monthlyStatistics[] = [
                'month' => $month,
                'user_count' => $userCount,
            ];
        }

        return response()->json($monthlyStatistics);
    }
    public function statistical_user_year()
    {
        $yearlyStatistics = [];

    $currentYear = date('Y');
    $startYear = $currentYear - 10; // Thống kê trong vòng 10 năm

    for ($year = $startYear; $year <= $currentYear; $year++) {
        $userCount = User::whereYear('created_at', $year)->count();
        $yearlyStatistics[] = [
            'year' => $year,
            'user_count' => $userCount,
        ];
    }

    return response()->json($yearlyStatistics);
    }
    public function loginGoogle(Request $request)
    {
        $idToken = $request->input('idToken');

        // Gửi yêu cầu xác thực đến Google API
        $response = Http::post('https://www.googleapis.com/oauth2/v3/tokeninfo', [
            'id_token' => $idToken,
        ]);

        if ($response->successful()) {
            $userInfo = $response->json();

            // Xác thực thành công, tạo hoặc cập nhật người dùng trong hệ thống của bạn
            // $userInfo chứa thông tin người dùng từ Google

            // Xác thực Passport thông qua email người dùng
            $user = User::where('email', $userInfo['email'])->first();
            if (!$user) {
                // Nếu người dùng chưa tồn tại, bạn có thể tạo mới tài khoản ở đây
                $user = User::create([
                    'email' => $userInfo['email'],
                    // Thêm các trường thông tin người dùng khác
                ]);
            }

            // Tạo token xác thực Passport cho người dùng
            $token = $user->createToken('GoogleToken')->accessToken;

            return response()->json([
                'accessToken' => $token,
            ]);
        } else {
            // Xử lý lỗi xác thực từ Google
            return response()->json([
                'error' => 'Authentication failed.',
            ], 401);
        }
    }
    public function loginFacebook(Request $request)
    {
        $accessToken = $request->input('accessToken');

        // Gửi yêu cầu xác thực đến Facebook API
        $response = Http::get("https://graph.facebook.com/v12.0/me?fields=id,name,email&access_token={$accessToken}");

        if ($response->successful()) {
            $userInfo = $response->json();

            // Xác thực thành công, tạo hoặc cập nhật người dùng trong hệ thống của bạn
            // $userInfo chứa thông tin người dùng từ Facebook
            // Xác thực Passport thông qua email người dùng
            $user = User::where('name', $userInfo['name'])->first();
            if (!$user) {
                // Nếu người dùng chưa tồn tại, bạn có thể tạo mới tài khoản ở đây
                $user = User::create([
                    'name' => $userInfo['name'],
                    // Thêm các trường thông tin người dùng khác
                ]);
            }

            // Tạo token xác thực Passport cho người dùng
            $token = $user->createToken('FacebookToken')->accessToken;

            return response()->json([
                'accessToken' => $token,
            ]);
        } else {
            // Xử lý lỗi xác thực từ Facebook
            return response()->json([
                'error' => 'Authentication failed.',
            ], 401);
        }
    }
}
