<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Carbon;
class ImageDetailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        // tạo ra 1 mảng
        $rules = [
            'id_hotel' => 'required | integer',
            'id_rooms' => 'required | integer',
            'id_services' => 'required|integer',
            'id_image' => 'required|integer',

        ];
        // lấy ra tên phương thức cần sử lý
        $currentAction = $this->route()->getActionMethod();
        switch ($this->method()):
            case 'POST':
                break;

            case 'PUT':
            case 'PATCH':
                break;
        endswitch;
        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên Không Được Để Trống',
            'description.required' => 'Mô Tả Không Được Để Trống',
            'star.required' => 'Sao Tầng Không Được Để Trống',
            'status.required' => 'Trạng Thái Không Được Để Trống',
            'quantity_floor.required' => 'Số Lượng Tầng Không Được Để Trống',
            'quantity_of_room.required' => 'Số Lượng Phòng Không Được Để Trống',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
            'messenger' => "Fail",
            "Sucess"=>false,
        ]));
    }
}