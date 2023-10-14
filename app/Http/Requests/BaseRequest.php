<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

//class BaseRequest extends FormRequest
//{
//    /**
//     * Determine if the user is authorized to make this request.
//     */
//    public function authorize(): bool
//    {
//        return false;
//    }
//
//    /**
//     * Get the validation rules that apply to the request.
//     *
//     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
//     */
//    public function rules(): array
//    {
//        return [
//            //
//        ];
//    }
//}

trait BaseRequest {
    public function message () {
        return [
            'name.required' => 'Tên Không Được Để Trống',
            'email.required' => 'Email không được để trống',
            'email.unique' => 'Email không được Trùng',
            'password.required' => 'Mật khẩu ko được để trống',
            'image.required' => 'Ảnh không được để trống',
            'image.mimes' => 'Ảnh không đúng định dạng',
            'date.before' => 'ngày sinh không được sau hiện tại',
            'cate_room.required' => 'Không được để trống danh mục phòng',

            // booking Detail
            'id_promotions.required' => 'Mã Hóa Đơn Không Được Để Trống',
            'id_room.required' => 'Mã Phòng Không Được Để Trống',
            'id_booking.required' => 'Mã Booking Không Được Để Trống',
            'id_services.required' => 'Dịch Vụ Không Được Để Trống',
            'nationality.required' => 'Quốc tịch không được để trống',
            'cccd.required' => 'CCCD không được để trống',
            'id_promotions.integer' => 'Mã Hóa Đơn Là Số',
            'id_room.integer' => 'Mã Phòng Là Số',
            'id_booking.integer' => 'Mã Booking Là Số',
            'id_services.integer' => 'Dịch Vụ Là Số',

            // Booking
            'check_in.required' => 'Check_in Không Được Để Trống',
            'check_in.after' => 'Check_in Không Được Nhỏ Hơn Hiện tại',
            'check_out.after' => 'check_out  phải sau ngày Check_in',
            'check_out.required' => 'Check_out Không Được Để Trống',
            'people_quantity.required' => ' Không Được Để Trống',
            'people_quantity.integer' => 'Phải Là kiểu số',
            'total_amount.integer' => 'Cần nhập số',
            'total_amount.required' => ' Không Được Để Trống',
            'phone.regex' => 'Số Sai Định Dạng',
            'phone.required' => 'Số Điện thoại Không Được Để Trống',

            // category
            'description.required' => 'Mô Tả Không Được Để Trống',

            //hotel
            'star.required' => 'Sao Tầng Không Được Để Trống',
            'status.required' => 'Trạng Thái Không Được Để Trống',
            'quantity_floor.required' => 'Số Lượng Tầng Không Được Để Trống',
            'quantity_of_room.required' => 'Số Lượng Phòng Không Được Để Trống',
            'price.required' => 'Giá Không Được Để Trống',
            'acreage.required' => 'Diện Tích Không được Để Trống',
            'floor.required' => 'Tầng Để Trống',

            //city

            // distinct

            //rate
            'content.required' => 'Nội dung không được để trống',
            'rating.required' => 'Đánh giá không được để trống'

        ];
    }
    protected function failedValidation(Validator $validator)
    {

          throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
            'messenger' => "Fail",
            "Success"=>false,
        ]));
    }
}
