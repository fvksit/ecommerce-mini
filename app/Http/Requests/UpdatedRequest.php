<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdatedRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
        ];

        $user = User::find($this->route('user'));

        if ($user) {
            if ($this->filled('email') && $this->input('email') !== $user->email) {
                $rules['email'] = 'required|email|unique:users,email';
            }
        } else {
            abort(404, 'User not found');
        }

        return $rules;
    }
}
