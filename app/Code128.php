<?php


namespace App;


class Code128
{
    public const CODE_128_CHARS = [
        ' ', '!', "\"", "#", "$", "%",
        "&", "'", "(", ")", "*", "+",
        ",", "-", ".", "/", "0", "1",
        "2", "3", "4", "5", "6", "7",
        "8", "9", ":", ";", "<", "=",
        ">", "?", "@", "A", "B", "C",
        "D", "E", "F", "G", "H", "I",
        "J", "K", "L", "M", "N", "O",
        "P", "Q", "R", "S", "T", "U",
        "V", "W", "X", "Y", "Z", "[",
        "\\", "]", "^", "_", "`", "a",
        "b", "c", "d", "e", "f", "g",
        "h", "i", "j", "k", "l", "m",
        "n", "o", "p", "q", "r", "s",
        "t", "u", "v", "w", "x", "y",
        "z", "{", "|", "}", "~", "&#195;",
        "&#196", "&#197", "&#198", "&#199",
        "&#200", "&#201", "&#202"
    ];

    public static function generateChecksum($input) {
        $sum = 104;
        $ca = str_split($input);
        $weight = 1;
        foreach ($ca as $c) {
            $ind = array_search($c . "", self::CODE_128_CHARS);
            //error_log($c . " is " . $ind);
            if(!$ind) return null;
            $sum += $ind*$weight;
            $weight++;
        }
        return self::CODE_128_CHARS[$sum % 103];
    }
}
