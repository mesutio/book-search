<?php

namespace App\Helper;

class StringHelper
{
    public static function cleanupSpecialChars(string $string, string $replacement = ' '): string
    {
        $specialChars = [
            '&amp;',
            '--',
            '&quot;',
            '!',
            '@',
            '#',
            '$',
            '%',
            '^',
            '&',
            '*',
            '(',
            ')',
            '_',
            '+',
            '{',
            '}',
            '|',
            ':',
            '"',
            '<',
            '>',
            '?',
            '[',
            ']',
            '\\',
            ';',
            "'",
            ',',
            '.',
            '/',
            '*',
            '+',
            '~',
            '`',
            '=',
            '"',
        ];

        return str_replace($specialChars, $replacement, $string);
    }

    public static function truncate(string $text, int $maxChar, string $end = '...'): string
    {
        if ('' !== $text && mb_strlen($text) > $maxChar) {
            $words = preg_split('/\s/u', $text);
            $output = '';
            $i = 0;
            while ($i < count($words)) {
                $length = mb_strlen($output) + mb_strlen($words[$i]);
                if ($length > $maxChar) {
                    break;
                }
                $output .= sprintf('%s%s', $i > 0 ? ' ' : '', $words[$i]);
                ++$i;
            }
            $output .= $end;
        } else {
            $output = $text;
        }

        return $output;
    }
}