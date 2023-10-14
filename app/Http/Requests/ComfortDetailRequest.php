<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComfortDetailRequest extends FormRequest
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
            'id_cate_room' => 'required|integer',
            'id_comfort' => 'required|integer',
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
        return $this->message();
    }
     // Bạn có thể gọi phương thức failedValidation từ đây
     protected function handleFailedValidation($validator)
    {
        $this->failedValidation($validator);
    }
}
