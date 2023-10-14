<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Carbon;

class HotelRequest extends FormRequest
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
    public function rules(): array
    {
        // tạo ra 1 mảng
        $rules = [
            'name' => 'required',
            'description' => 'required',
            'address' => 'required',
            'quantity_of_room' => 'required|integer',
            'id_city' => 'required|integer',
            'phone' => 'required | regex:/^([0-9\s\-\+\(\)]*)$/',
            'email' => 'required|email|unique:users,email',
            'star' => 'required|integer',
            'status' => 'required|integer',
            'quantity_floor' => 'required|integer',
        ];
        // lấy ra tên phương thức cần sử lý
        $currentAction = $this->route()->getActionMethod();
        switch ($this->method()):
            case 'POST':
                break;

            case 'PUT':
            case 'PATCH':
                if ($currentAction == 'updateState_hotel') {
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
