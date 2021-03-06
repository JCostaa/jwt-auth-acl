<?php

namespace Laravelha\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    public function authorize()
    {
        $ability = $this->method() . '|' . $this->route()->uri;

        return $this->user()->can($ability);
    }

    public function rules()
    {
        $user = $this->route('user');

        return [
            'name' => 'required|string|max:225',
            'email' => 'required|string|email|max:225',
            'email_verified_at' => 'nullable',
            'password' => 'required|string|min:8|unique:users,email,' . $user->id,
            'remember_token' => 'nullable|string|max:100',
            'roles' => 'sometimes|required',
        ];
    }
}
