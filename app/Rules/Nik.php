<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\InvokableRule;

class Nik implements InvokableRule, DataAwareRule
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

        $birth_date_of_nik = substr($value, 6, 6);
        $birth_date = Carbon::parse($this->data['birth_date'])->format('dmy');
        if ($this->data['gender'] == 'female') {
            $birth_date += 400000;
        }
        if ($birth_date_of_nik != $birth_date) {
            $fail('NIK tidak sesuai dengan tanggal lahir');
        }
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }
}
