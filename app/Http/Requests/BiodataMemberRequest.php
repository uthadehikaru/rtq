<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BiodataMemberRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'member_id' => 'required|exists:members,id',
            'nik' => 'required|size:16',
            'birth_date' => 'required|date',
            'profile_picture' => 'required|image|max:4068',
        ];
    }
}
