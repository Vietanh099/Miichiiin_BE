<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CategoryRoomRequest extends FormRequest
{
    use BaseRequest;
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
    public function rules()
    {
        // tạo ra 1 mảng
        $rules = [
            'name' => 'required',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp',
            'floor' => 'required|integer',
            'acreage' => 'required|integer',
            'price' => 'required|integer',
            'quantity_of_people' => 'required|integer',
            'short_description' => 'required',
        ];
        // lấy ra tên phương thức cần sử lý
        $currentAction = $this->route()->getActionMethod();
        switch ($this->method()):
            case 'POST':
                if ($currentAction == 'find') {
                    $rules = [
                        'check_in' => 'required',
                        'check_out' => 'required',
                        'id_hotel' => 'required|integer',
                        'number_people' => 'required|integer',
                        'total_room' => 'required|integer',
                    ];
                }
                if ($currentAction == 'update') {
                    $rules = [
                        'check_in' => 'required',
                        'check_out' => 'required',
                        'image' => 'mimes:jpg,jpeg,png,webp',
                        'id_hotel' => 'required|integer',
                        'number_people' => 'required|integer',
                        'total_room' => 'required|integer',
                    ];
                }
                if ($currentAction == 'store_image_cate') {
                    $rules = [];
                }
                break;

            case 'PUT':
            case 'PATCH':
                if ($currentAction == 'update') {
                    $rules['image'] = 'mimes:jpg,jpeg,png,webp';
                }
                if ($currentAction == 'updateState_cate') {
                    $rules = [
                        'status' => 'required',
                    ];
                }
                break;
        endswitch;
        return $rules;
    }
    public function messages()
    {
        return $this->message();
    }
    protected function handleFailedValidation($validator)
    {
        $this->failedValidation($validator);
    }
}
