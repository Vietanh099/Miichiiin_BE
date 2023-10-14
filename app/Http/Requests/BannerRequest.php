<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
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
            'image' => 'required|mimes:jpg,jpeg,png|max:2048',
            'alt' => 'required'
        ];
        // lấy ra tên phương thức cần sử lý
        $currentAction = $this->route()->getActionMethod();
        switch ($this->method()):
            case 'POST':
                break;

            case 'PUT':
            case 'PATCH':
                if ($currentAction == 'update') {
                    $rules['image'] = 'mimes:jpg,jpeg,png,webp|max:2048';
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
