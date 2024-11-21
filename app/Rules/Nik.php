<?php

namespace App\Rules;

use App\Models\Member;
use App\Models\Registration;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\InvokableRule;

class Nik implements DataAwareRule, InvokableRule
{
    protected $data = [];

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        if (strlen($value) != 16) {
            $fail('NIK harus 16 karakter');
        }

        $registration = Registration::where('nik', $value)->first();
        if ($registration) {
            $fail('NIK sudah terdaftar, mohon menggunakan NIK lain atau hubungi admin untuk informasi lebih lanjut');
        }

        $member = Member::where('nik', $value)->first();
        if ($member) {
            $fail('NIK sudah terdaftar, mohon menggunakan NIK lain atau hubungi admin untuk informasi lebih lanjut');
        }
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }
}
