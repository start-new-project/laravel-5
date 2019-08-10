<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (auth()->user()->isAdmin());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|unique:users|min:3|max:100',
            'email' => 'required|email|unique:users|max:100',
            'password' => 'required|min:6|max:100',
            'active' => 'boolean',
            'gender' => 'required|boolean',
            'role' => ['required',Rule::in(['user', 'student', 'client', 'admin'])],
            'birthday' => 'required|date',
        ];
    }
}
