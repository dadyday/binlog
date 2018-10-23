<?php
namespace Binlog;

class Bin {
	static $version = 2;

    static function formatSize(array $aFormat) {
        $size = 0;
        foreach ($aFormat as $param => $format) {
            $size += static::size($format);
        }
        return $size;
    }

    protected static function type($format, &$type, &$size) {
        if (!preg_match('~^(uint|int|byte|str)<?(\d+)>?$~', $format, $aMatch)) {
            throw new \Exception("unknown bin format $format");
        }
        list(, $type, $size) = $aMatch;
        switch ($type.$size) {
            case 'uint1': $type = 'C'; break;
            case 'int1': $type = 'c'; break;
            case 'uint2': $type = 'S'; break;
            case 'int2': $type = 's'; break;
            case 'uint4': $type = 'L'; break;
            case 'int4': $type = 'l'; break;
            case 'uint8': $type = 'Q'; break;
            case 'int8': $type = 'q'; break;
            default:
                switch ($type) {
                    case 'str':
                        $type = 'Z'.$size; break;
                }
        }
    }

    static function size($format) {
        static::type($format, $type, $size);
        return $size;
    }

    static function parse($format, $data) {
        static::type($format, $type, $size);
        $data = unpack($type, $data);
        bdump($data);
        return count($data) == 1 ? $data[1] : $data;
    }





/*
        if ($endianness === true) {  // big-endian
            $i = $f("n", $i);
        else if ($endianness === false) {  // little-endian
            $i = $f("v", $i);
        if ($endianness === true) {  // big-endian
            $i = $f("N", $i);
        else if ($endianness === false) {  // little-endian
            $i = $f("V", $i);

        if ($endianness === true) {  // big-endian
            $i = $f("J", $i);
        else if ($endianness === false) {  // little-endian
            $i = $f("P", $i);
*/
}
