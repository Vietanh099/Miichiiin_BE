<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\hotel;
use App\Models\Voucher;
use App\Models\WalletVoucher;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class WalletController extends Controller
{
    public function create_wallet($id_user) {
        $wallet = new Wallet();
        $wallet->id_user = $id_user;
        $wallet->save();
        if ($wallet->id) {
            return response()->json($wallet);
        }
        return response()->json(
            ["error_message" => "Không thể tạo ví người dùng"]
        , Response::HTTP_BAD_REQUEST);
    }

    public function topup_coin_api(Request $request) {
        $coin = $request->coin;
        $id_user = $request->id_user;
        if (!isset($id_user) || !is_int($id_user)) {
            return response()->json([
                "error_message" => "Tham số id_user không hợp lệ"
            ], Response::HTTP_BAD_REQUEST);
        }
        if (!isset($coin) || !is_int($coin) || $coin <= 0) {
            return response()->json([
                "error_message" => "Tham số coin không hợp lệ"
            ], Response::HTTP_BAD_REQUEST);
        }
        $wallet = topup_coin($id_user, $coin);
        return response()->json($wallet);
    }

    public function add_voucher_to_wallet(Request $request) {
        $type_of_users = $request->types;
        $id_voucher = $request->voucher;
        $voucher = Voucher::query()->find($id_voucher);
        if (!is_int($id_voucher)) {
            return response()->json(
                ["error_message" => "Id của voucher không hợp lệ"]
                , Response::HTTP_BAD_REQUEST);
        }

        $list_user = [];
        foreach ($type_of_users as $type) {
            switch ($type) {
                case 'all':
                    $list_user[] = User::query()
                        ->select('id')
                        ->get();
                    break;
                case 'new_customer':
                    $list_user[] = User::query()
                        ->select('id')
                        ->whereBetween('created_at', [now()->subDays(30), now()])
                        ->get();
                    break;
                case 'date':
                    $end_date = $request->start_date ?? now()->subYear();
                    $start_date = $request->end_date ?? now()->subDays(30)->subYear();
                    $list_user[] = User::query()
                        ->join('bookings', 'bookings.id_user', '=', 'users.id')
                        ->select('users.id')
                        ->whereBetween('bookings.created_at', [$start_date, $end_date])
                        ->get();
                    break;
                case 'quantity_of_booking':
                    $quantity = $request->quantity_of_booking ?? 5;
                    $list_user[] = User::query()
                        ->select('users.id as id_user', DB::raw('count(bookings.id) as quantity_booking'))
                        ->join('bookings', 'users.id', '=', 'bookings.id_user')
                        ->groupBy('users.id')
                        ->having('quantity_booking', '>=', $quantity)
                        ->pluck('users.id');
                    break;
                case 'total_of_amount':
                    $amount = $request->amount ?? 5000000;
                    $list_user[] = User::query()
                        ->select('users.id as id_user', DB::raw('sum(bookings.total_amount) as amount'))
                        ->join('bookings', 'users.id', '=', 'bookings.id_user')
                        ->groupBy('users.id')
                        ->having('amount', '>=', $amount)
                        ->pluck('users.id');
                    break;
                case 'area':
                    $area = $request->area ?? "hà nội";
                    $list_user[] = User::query()
                        ->select('id')
                        ->where('address', 'like', '%'.strtolower($area).'%')
                        ->get();
                    break;
                default:
                    break;
            }
        }

        $collection = collect($list_user);
        $array_user = $collection->flatten()->all();
        $minus = check_quantity_voucher($id_voucher, count($array_user));
        if (!$minus) {
            return response()->json(
                ["error_message" => "Số lượng voucher trên hệ thống không đủ"]
                , Response::HTTP_BAD_REQUEST);
        }
        $count = 0;
        foreach ($array_user as $user) {
            $wallet = get_wallet_via_user($user->id);
            $check_voucher = $this->check_voucher_in_wallet($wallet->id, $id_voucher);
            if ($check_voucher) {
                continue;
            }
            $data = [
                "id_wallet" => $wallet->id,
                "id_voucher" => $id_voucher
            ];
            WalletVoucher::query()->create($data);
            $voucher->quantity -= 1;
            $count += 1;
        }
        $voucher->save();
        return response()->json(
            ["message" => "Đã phát voucher thành công cho " . $count . " người dùng"]
        );
    }

    public function check_voucher_in_wallet($id_wallet, $id_voucher) {
        $list_voucher = WalletVoucher::query()
            ->select('*')
            ->where('id_wallet', $id_wallet)
            ->get();
        return collect($list_voucher)->contains('id_voucher', $id_voucher);
    }
}
