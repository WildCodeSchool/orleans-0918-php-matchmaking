<?php

namespace App\Service;

class PasswordGenerator
{
    /**
     * Generate password
     * @param int $length
     * @return string
     */
    public function generate(int $length): string
    {
        $lowercase = range("a", "z");
        $uppercase = range('A', 'Z');
        $numbers = range(0, 9);
        $password = '';

        $password .= $lowercase[array_rand($lowercase)];
        $password .= $uppercase[array_rand($uppercase)];
        $password .= $numbers[array_rand($numbers)];
        $characters = array_merge($lowercase, $uppercase, $numbers);

        for ($i = 3; $i < $length; $i++) {
            $password .= $characters[array_rand($characters)];
        }

        $password = str_shuffle($password);

        return $password;
    }
}
