<?php

namespace App\Utils;

class Censurator
{
    const BAD_WORDS = ["shit", "purée", "putain", "viagra"];

    public function purify(string $text): string
    {
        foreach(self::BAD_WORDS as $badWord){
            $replacement =  mb_substr($badWord, 0, 1) . str_repeat("*", mb_strlen($badWord)-1);
            $text = str_ireplace($badWord, $replacement, $text);
        }

        return $text;
    }
}