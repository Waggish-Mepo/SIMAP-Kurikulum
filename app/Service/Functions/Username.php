<?php

namespace App\Service\Functions;

use App\Models\User;
use Illuminate\Support\Str;

class Username
{
    public static function generateUsername(String $name)
    {
        if (Str::length($name) < 10) {
            $name = str_repeat($name, 5);
        }

        $prefix = Str::substr(Str::slug(trim($name), ''), 0, 9);

        // random 3 number with format 001 010 100
        $randNumber = str_pad(mt_rand(0, 999), 3, '0', STR_PAD_LEFT);

        $username = $prefix . sprintf('%2s', $randNumber);

        // check for uniqueness
        $exists = User::where('username', $username)->exists();
        if (!$exists) {
            return $username;
        }

        $counter = 0;
        $randomized = '';
        while ($counter <= 5) {
            $randNumber = str_pad(mt_rand(0, 999), 3, '0', STR_PAD_LEFT);
            $randomized = $prefix . sprintf('%2s', $randNumber);
            $exists = User::where('username', $randomized)->exists();
            if (!$exists) {
                return $randomized;
            }

            $counter++;
        }

        return $randomized;
    }
}
