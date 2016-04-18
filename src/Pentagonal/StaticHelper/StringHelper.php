<?php
namespace Pentagonal\StaticHelper;

/**
 * Class StringHelper
 *
 * @package Pentagonal\StaticHelper
 */
class StringHelper
{
    /**
     * Remove Invisible Characters
     *
     * This prevents sandwiching null characters
     * between ascii characters, like Java\0script.
     * @access  public
     * @param   string
     * @return string
     */
    public static function removeInvisibleCharacters($str, $url_encoded = true)
    {
        $non_displayables = array();

        // every control character except newline (dec 10)
        // carriage return (dec 13), and horizontal tab (dec 09)

        if ($url_encoded) {
            $non_displayables[] = '/%0[0-8bcef]/';  // url encoded 00-08, 11, 12, 14, 15
            $non_displayables[] = '/%1[0-9a-f]/';   // url encoded 16-31
        }

        $non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';   // 00-08, 11, 12, 14-31, 127

        do {
            $str = preg_replace($non_displayables, '', $str, -1, $count);
        } while ($count);

        return $str;
    }

    /**
     * Generate random string
     *
     * @param  string $length       numeric as length of string output ( numeric )
     * @param  string $type         mix, numeric, uppercase, lowercase or, alphabet
     * @param  string $chars        for custom characters use to random
     * @return string $randomstring
     */
    public static function randomString($length = 64, $type = 'mix', $chars = null)
    {
        if (! is_numeric($length)) {
            $length = 64;
        }
        // check type
        $type = !is_string($type) ? 'mix' : trim(strtolower($type));

        $numeric      = '0123456789'; // numeric
        $uppercase    = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; // uppercase characters
        $lowercase    = 'abcdefghijklmnopqrstuvwxyz'; // lowercase characters
        $alphabet     = $uppercase.$lowercase; // alphabetical characters
        $alphanumeric = $alphabet.$numeric;
        $mix          = $alphanumeric.' ~`!@#$%^&*()_+-={}[]:;|<>?/.,';// mix characters

        // define the characters
        $characters = $mix;
        $arr  = array(
            'numeric'   => $numeric,
            'upper' => $uppercase,
            'uppercase' => $uppercase,
            'lower' => $lowercase,
            'lowercase' => $lowercase,
            'alphabet'  => $alphabet,
            'alphanumeric'  => $alphanumeric,
            'alnum'     => $alphanumeric,
        );

        if (array_key_exists($type, $arr)) {
            $characters = $arr[$type];
        }

        if ($chars && ! empty($chars) && is_string($chars)) {
            $characters = $chars;
        }

        $randomstring = ''; # Empty reset string
        // run random
        for ($i = 0; $i < $length; $i++) {
            $randomstring .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomstring;
    }

    /**
     * Indents a flat JSON string to make it more human-readable.
     *
     * @param  string $json The original JSON string to process.
     * @return string Indented version of the original JSON string.
     */
    public static function prettyJson($json)
    {
        /* Must be as string
         * if is not string will be return as original
        ---------------------------------------------- */
        if (!is_string($json)) {
            return $json;
        }

        static $result,
        $tmpjson;

        if ($result  && $tmpjson === $json) {
            return $result;
        }

        $tmpjson     = $json;           # create temporary variable $json
        $result      = '';              # reset result
        $pos         = 0;               # get pos
        $strLen      = strlen($json); # count the length of jsonp
        $indentStr   = '  ';            # the indentation of jsonp
        $newLine     = "\n";            # new line
        $prevChar    = '';              # empty
        $outOfQuotes = true;            # set out of quotes

        for ($i=0; $i <= $strLen; $i++) {
            // Grab the next character in the string.
            $char = substr($json, $i, 1);

            // Are we inside a quoted string?
            if ($char == '"' && $prevChar != '\\') {
                $outOfQuotes = !$outOfQuotes;

                // If this character is the end of an element,
                // output a new line and indent the next line.
            } elseif (($char == '}' || $char == ']') && $outOfQuotes) {
                $result .= $newLine;
                $pos --;
                for ($j=0; $j<$pos; $j++) {
                    $result .= $indentStr;
                }
            }

            // Add the character to the result string.
            $result .= $char;

            // If the last character was the beginning of an element,
            // output a new line and indent the next line.
            if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
                $result .= $newLine;
                if ($char == '{' || $char == '[') {
                    $pos ++;
                }

                for ($j = 0; $j < $pos; $j++) {
                    $result .= $indentStr;
                }
            }

            $prevChar = $char;
        }

        return $result;
    }

    /* --------------------------------------------------------------------------------*
     |                              Serialize Helper                                   |
     |                                                                                 |
     | Custom From WordPress Core wp-includes/functions.php                            |
     |---------------------------------------------------------------------------------|
     */

    /**
     * Serialize data, if needed. @uses for ( uncompress serialize values )
     *
     * @param  mixed $data Data that might be serialized.
     * @return mixed A scalar data
     */
    public static function maybeSerialize($data)
    {
        if (is_array($data) || is_object($data)) {
            return @serialize($data);
        }

        // Double serialization is required for backward compatibility.
        if (static::isSerialized($data, false)) {
            return serialize($data);
        }

        return $data;
    }

    /**
     * Unserialize value only if it was serialized.
     *
     * @param  string $original Maybe unserialized original, if is needed.
     * @return mixed  Unserialized data can be any type.
     */
    public static function maybeUnserialize($original)
    {
        // don't attempt to unserialize data that wasn't serialized going in
        if (static::isSerialized($original)) {
            return @unserialize($original);
        }

        return $original;
    }

    /**
     * Check value to find if it was serialized.
     * If $data is not an string, then returned value will always be false.
     * Serialized data is always a string.
     *
     * @param  mixed $data   Value to check to see if was serialized.
     * @param  bool  $strict Optional. Whether to be strict about the end of the string. Defaults true.
     * @return bool  False if not serialized and true if it was.
     */
    public static function isSerialized($data, $strict = true)
    {
        /* if it isn't a string, it isn't serialized
         ------------------------------------------- */
        if (! is_string($data)) {
            return false;
        }

        $data = trim($data);

        if ('N;' == $data) {
            return true;
        }

        if (strlen($data) < 4 || ':' !== $data[1]) {
            return false;
        }

        if ($strict) {
            $lastc = substr($data, -1);
            if (';' !== $lastc && '}' !== $lastc) {
                return false;
            }
        } else {
            $semicolon = strpos($data, ';');
            $brace     = strpos($data, '}');

            // Either ; or } must exist.
            if (false === $semicolon && false === $brace
                || false !== $semicolon && $semicolon < 3
                || false !== $brace && $brace < 4
            ) {
                return false;
            }
        }

        $token = $data[0];
        switch ($token) {
            case 's':
                if ($strict) {
                    if ('"' !== substr($data, -2, 1)) {
                        return false;
                    }
                } elseif (false === strpos($data, '"')) {
                    return false;
                }
            // or else fall through
            case 'a':
            case 'O':
                return (bool) preg_match("/^{$token}:[0-9]+:/s", $data);

            case 'b':
            case 'i':
            case 'd':
                $end = $strict ? '$' : '';

                return (bool) preg_match("/^{$token}:[0-9.E-]+;$end/", $data);
        }

        return false;
    }

    /**
     * Convert number of bytes largest unit bytes will fit into.
     *
     * It is easier to read 1 kB than 1024 bytes and 1 MB than 1048576 bytes. Converts
     * number of bytes to human readable number by taking the number of that unit
     * that the bytes will go into it. Supports TB value.
     *
     * Please note that integers in PHP are limited to 32 bits, unless they are on
     * 64 bit architecture, then they have 64 bit size. If you need to place the
     * larger size then what PHP integer type will hold, then use a string. It will
     * be converted to a double, which should always have 64 bit length.
     *
     * Technically the correct unit names for powers of 1024 are KiB, MiB etc.
     *
     * @param int|string $bytes    Number of bytes. Note max integer size for integers.
     * @param int        $decimals Optional. Precision of number of decimal places. Default 0.
     * @return string|false False on failure. Number string on success.
     */
    public static function sizeFormat($bytes, $decimals = 0, $decimal_point = '.', $thousands_sep = ',')
    {
        $quant = array(
            // ========================= Origin ====
            'TB' => 1099511627776,  // pow( 1024, 4)
            'GB' => 1073741824,     // pow( 1024, 3)
            'MB' => 1048576,        // pow( 1024, 2)
            'kB' => 1024,           // pow( 1024, 1)
            'B'  => 1,              // pow( 1024, 0)
        );
        /**
         * Check and did
         */
        foreach ($quant as $unit => $mag) {
            if (doubleval($bytes) >= $mag) {
                return number_format(
                    ($bytes / $mag),
                    abs(intval($decimals)),
                    $decimal_point,
                    $thousands_sep
                ). ' ' . $unit;
            }
        }

        return false;
    }

    /**
     * Deep mixed string or array to lowercase ( array keys also array values )
     *
     * @param  mixed $value  as to lower content
     * @param  bool  $usekey if as array use key true to make it lower also
     * @return mixed $result
     */
    public static function strtoLower($value, $usekey = false)
    {
        if (is_array($value)) {
            # else if value is array
            // reset result
            // split it with loop
            foreach ($value as $key => $val) {
                $key = $usekey ? self::strtoLower($key) : $key;
                $value[$key] = self::strtoLower($val); # callback to self function
            }
        }
        if (is_object($value)) {
            foreach (get_object_vars($value) as $key => $val) {
                $value->{$key} = self::strtoLower($val); # callback to self function
            }
        }
        if (is_string($value)) {
            $value = strtolower($value);
        }

        return $value;
    }

    /**
     * Deep mixed string or array to uppercase ( array keys also array values )
     *
     * @param  mixed $value  as to lower content
     * @param  bool  $usekey if as array use key true to make it upper also
     * @return mixed $result
     */
    public static function strtoUpper($value, $usekey = false)
    {
        if (is_array($value)) {
            # else if value is array
            // reset result
            // split it with loop
            foreach ($value as $key => $val) {
                $key = $usekey ? self::strtoUpper($key) : $key;
                $value[$key] = self::strtoUpper($val); # callback to self function
            }
        }
        if (is_object($value)) {
            foreach (get_object_vars($value) as $key => $val) {
                $value->{$key} = self::strtoUpper($val); # callback to self function
            }
        }

        if (is_string($value)) {
            $value = strtoupper($value);
        }

        return $value;
    }

    /**
     * Deep str_replace
     * alternative for str replace for array values
     *
     * @param  string             $search   for the target on $object
     * @param  string             $replacer $replace the target search match
     * @param  mixed array|string $object   target for replace
     * @return mixed              str_replace deep
     */
    public static function strReplace($search, $replacer, $object)
    {
        if (! $object) {
            return $object;
        }
        if (is_array($object)) {
            # else if value is array
            // reset result
            // split it with loop
            foreach ($object as $key => $val) {
                $object[$key] = self::strReplace($search, $replacer, $val); # callback to self function
            }
        }
        if (is_object($value)) {
            foreach (get_object_vars($object) as $key => $val) {
                $object->{$key} = self::strReplace($search, $replacer, $val); # callback to self function
            }
        }
        if (is_string($value) || ! is_numeric($value)) {
            $object = str_replace($search, $replacer, $object);
        }

        return $object;
    }

    /**
     * str_split() [php function aliases]
     *
     * @param $string
     * @param int $split_length
     * @return array|bool
     */
    public static function strSplit($string, $split_length = 1)
    {
        if (function_exists('str_split')) {
            return str_split($string, $split_length);
        }
        $result = false;
        $sign = (($split_length < 0) ? -1 : 1);
        $strlen = strlen($string);
        $split_length = abs($split_length);
        if (($split_length == 0) || ($strlen == 0)) {
            $result = false;
        } elseif ($split_length >= $strlen) {
            $result[] = $string;
        } else {
            $length = $split_length;
            for ($i = 0; $i < $strlen; $i++) {
                $i = (($sign < 0) ? $i + $length : $i);
                $result[] = substr($string, $sign*$i, $length);
                $i--;
                $i = (($sign < 0) ? $i : $i + $length);
                if (($i + $split_length) > ($strlen)) {
                    $length = $strlen - ($i + 1);
                } else {
                    $length = $split_length;
                }
            }
        }

        return $result;
    }

    /**
     * Navigates through an array and removes slashes from the values.
     *
     * If an array is passed, the array_map() function causes a callback to pass the
     * value back to the function. The slashes from this value will removed.
     *
     * @param  mixed $value The value to be stripped.
     * @return mixed Stripped value.
     */
    public static function stripslashes($value)
    {
        if (is_array($value)) {
            foreach ($value as $key => $val) {
                $value[$key] = self::stripslashes($val); # callback to self function
            }
        }
        if (is_object($value)) {
            foreach (get_object_vars($value) as $key => $data) {
                $value->{$key} = self::stripslashes($data);
            }
        }
        if (is_string($value)) {
            $value = stripslashes($value);
        }

        return $value;
    }

    /**
     * Decode URL Deep
     *
     * @param  mixed $value as value
     * @return mixed        decoded URL value
     */
    public static function urlDecode($value)
    {
        if (is_array($value)) {
            foreach ($value as $key => $val) {
                $value[$key] = self::urlDecode($val); # callback to self function
            }
        }
        if (is_object($value)) {
            foreach (get_object_vars($value) as $key => $data) {
                $value->{$key} = self::urlDecode($data);
            }
        }

        if (is_string($value)) {
            $value = urldecode($value);
            if (strpos($value, '%2') !== false) {
                $value = self::urlDecode($value);
            }
        }

        return $value;
    }

    /**
     * Encode URL Deep
     *
     * @param  mixed $value as value
     * @return mixed        encoded URL value
     */
    public static function urlEncode($value)
    {
        if (is_array($value)) {
            foreach ($value as $key => $val) {
                $value[$key] = self::urlEncode($val); # callback to self function
            }
        }
        if (is_object($value)) {
            foreach (get_object_vars($value) as $key => $data) {
                $value->{$key} = self::urlEncode($data);
            }
        }

        if (is_string($value)) {
            $value = urlencode($value);
        }

        return $value;
    }

    /**
     * Encode URL Deep
     *
     * @param  mixed $value as value
     * @return mixed        raw encoded URL value
     */
    public static function rawUrlEncode($value)
    {
        if (is_array($value)) {
            foreach ($value as $key => $val) {
                $value[$key] = self::rawUrlEncode($val); # callback to self function
            }
        }
        if (is_object($value)) {
            foreach (get_object_vars($value) as $key => $data) {
                $value->{$key} = self::rawUrlEncode($data);
            }
        }

        if (is_string($value)) {
            $value = rawurlencode($value);
        }

        return $value;
    }
}
