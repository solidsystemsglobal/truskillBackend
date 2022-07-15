<?php

namespace App\Http\Requests\Api\V1\Auth;

use App\Http\Requests\Api\Request;

class RegisterRequest extends Request
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
            'email' => 'required|string|email:rfc,dns|unique:users,email',
            'name' => 'required|string|max:255|unique:users,name',
            'password' => 'required|string|min:8|confirmed'
        ];
    }
}
