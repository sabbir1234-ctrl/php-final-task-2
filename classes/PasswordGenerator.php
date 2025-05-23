<?php
class PasswordGenerator {
    public static function generate($length, $lower, $upper, $nums, $specials) {
        $chars = [
            'lower' => 'abcdefghijklmnopqrstuvwxyz',
            'upper' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'nums' => '0123456789',
            'specials' => '!@#$%^&*()_-+=<>?'
        ];

        $password = '';
        $password .= self::getRandom($chars['lower'], $lower);
        $password .= self::getRandom($chars['upper'], $upper);
        $password .= self::getRandom($chars['nums'], $nums);
        $password .= self::getRandom($chars['specials'], $specials);
        $remaining = $length - strlen($password);
        $all = implode('', $chars);
        $password .= self::getRandom($all, $remaining);
        return str_shuffle($password);
    }

    private static function getRandom($set, $count) {
        return substr(str_shuffle(str_repeat($set, ceil($count / strlen($set)))), 0, $count);
    }
}
