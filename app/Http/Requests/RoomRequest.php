<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Carbon;

class RoomRequest extends FormRequest
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
            'description' => 'required',
            'name' => 'required',
            'id_floor' => 'required|integer',
            'id_hotel' => 'required|integer',
            'likes' => 'required|integer',
            'acreage' => 'required|integer',
            'price' => 'required|integer',
            'quantity_of_people' => 'required|integer',
             'views' => 'required',
            'status' => 'required|integer',
            'id_cate' => 'required',
            'short_description' => 'required',
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