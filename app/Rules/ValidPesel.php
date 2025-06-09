<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidPesel implements Rule
{
    public function passes($attribute, $value): bool
    {
        if (!preg_match('/^\d{11}$/', $value)) {
            return false;
        }

        $weights = [1, 3, 7, 9, 1, 3, 7, 9, 1, 3];
        $sum = 0;

        for ($i = 0; $i < 10; $i++) {
            $sum += intval($value[$i]) * $weights[$i];
        }

        $checksum = (10 - ($sum % 10)) % 10;

        return intval($value[10]) === $checksum;
    }

    public function message(): string
    {
        return 'Pole :attribute nie zawiera poprawnego numeru PESEL.';
    }
}
