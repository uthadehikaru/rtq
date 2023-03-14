<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class MemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->hasRole('administrator');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'member_no' => '',
            'nik' => 'nullable|size:16',
            'full_name' => 'required',
            'short_name' => '',
            'email' => '',
            'gender' => 'required',
            'birth_date' => '',
            'phone' => '',
            'address' => '',
            'postcode' => '',
            'school' => '',
            'class' => '',
            'level' => '',
            'batch_id' => '',
            'registration_date' => '',
            'status' => '',
            'profile_picture' => '',
        ];
    }
}
