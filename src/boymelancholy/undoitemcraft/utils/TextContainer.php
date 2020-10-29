<?php

declare(strict_types=1);

namespace boymelancholy\undoitemcraft\utils;

class TextContainer {

    private static $ini;

    public function __construct(string $filePath) {
        self::$ini = parse_ini_file($filePath);
    }

    /**
     * メッセージを返す
     *
     * @param string $key
     * @return string|null
     */
    public static function get(string $key) : ?string {
        try {
            $pref = (string) self::$ini['prefix'];
            $text = (string) self::$ini[$key];
            $text = str_replace('%PREFIX%', $pref, $text);
            return str_replace('$', self::convertUnicode('00A7'), $text);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Unicode変換
     *
     * @param $unicode
     * @return string
     */
    private static function convertUnicode($unicode) : string {
        $num = hexdec($unicode);
        if($num<=0x7F) return chr($num);
        if($num<=0x7FF) {
            return
                chr(($num>>6)+192).
                chr(($num&63)+128);
        }
        if($num<=0xFFFF) {
            return
                chr(($num>>12)+224).
                chr((($num>>6)&63)+128).
                chr(($num&63)+128);
        }
        if($num<=0x1FFFFF) {
            return
                chr(($num>>18)+240).
                chr((($num>>12)&63)+128).
                chr((($num>>6)&63)+128).
                chr(($num&63)+128);
        }
        return '';
    }
}