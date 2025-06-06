<?php

namespace Database\Factories\Providers;

use Faker\Provider\Base as BaseProvider;

class PeselProvider extends BaseProvider
{
    public function pesel(): string
    {
        $year = rand(1900, 2099);
        $month = rand(1, 12);
        $day = rand(1, 28); // uproszczenie — żeby unikać błędnych dat

        if ($year >= 2000 && $year <= 2099) {
            $month += 20;
        }

        $yearShort = str_pad($year % 100, 2, '0', STR_PAD_LEFT);
        $monthStr = str_pad($month, 2, '0', STR_PAD_LEFT);
        $dayStr = str_pad($day, 2, '0', STR_PAD_LEFT);

        $serial = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

        $partialPesel = $yearShort . $monthStr . $dayStr . $serial;

        $weights = [1, 3, 7, 9, 1, 3, 7, 9, 1, 3];

        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += intval($partialPesel[$i]) * $weights[$i];
        }

        $checksum = (10 - ($sum % 10)) % 10;

        return $partialPesel . $checksum;
    }
}
