<?php

if (! function_exists('temp_password')) {
    /**
     * Random temporary password for accounts created by HR/Admin.
     * Letters and digits that are easy to read aloud (no 0/O, 1/l/I), plus a symbol and digit.
     */
    function temp_password(): string
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789';
        $out   = '';
        for ($i = 0; $i < 12; $i++) {
            $out .= $chars[random_int(0, strlen($chars) - 1)];
        }

        return $out . '#7';
    }
}

if (! function_exists('full_name')) {
    function full_name(array $user): string
    {
        return trim($user['first_name'] . ' ' . ($user['middle_name'] ?? '') . ' ' . $user['last_name']);
    }
}
