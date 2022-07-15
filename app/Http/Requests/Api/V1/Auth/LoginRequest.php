<?php

namespace App\Http\Requests\Api\V1\Auth;

use App\Http\Requests\Api\Request;

class LoginRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|string|email:rfc,dns',
            'password' => 'required|string|min:8',
        ];
    }
}
