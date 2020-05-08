<?php
namespace nkkollaw\Utils;

class Strings {
    /**
    * Converts string into ASCII-only, replacing accented letters with their
    * ASCII counterparts.
    *
    * @param string $str String to be converted
    * @return string $str String converted into ASCII
    */
    public static function toAscii($str) {
        $a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
        $b = 'AAAAAAACEEEEIIIIDNOOOOOOUUUUYbsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
        $str = utf8_decode($str);
        $str = strtr($str, utf8_decode($a), $b);
        return utf8_encode($str);
    }

    /**
    * Hyphenize a string
    *
    * @param string $str String to be hyphenized
    * @return string $str Syphenized string
    */
    public static function hyphenize($str) {
        $str = trim($str);
        $str = str_replace(' ', '-', $str);
        $str = str_replace('--', '-', $str);

        return $str;
    }

    /**
    * Converts a string into camel case
    *
    * @param string $str String
    * @return string $str String converted into camel case
    */
    public static function toCamelCase($str, $pascal_case = false) {
        $str = str_replace(array('-', '_'), ' ', $str);
        $str = ucwords($str);
        $str = str_replace(' ', '', $str);
        if (!$pascal_case) {
            if (function_exists('lcfirst')) {
                return lcfirst($str);
            } else {
                return strtolower(substr($str, 0, 1)) . substr($str, 1);
            }
        }

        return $str;
    }

    /**
    * Converts a string into snake case
    *
    * @param string $str String
    * @return string $str String converted into snake case
    */
    public static function toSnakeCase($str) {
        $str = self::hyphenize($str);
        $str = str_replace('-', '_', $str);
    }

    /**
    * Converts a camel case string into snake case.
    *
    * Treats numbers as a separate word, e.g. "player22" becomes "player_22".
    *
    * @param string $str String in camel case format
    * @return string $str String converted into snake case
    */
    public static function camelToSnake($str) {
        $str = preg_replace('/[A-Z]/', '_$0', $str);
        $str = preg_replace('/(\d+)/', '_$1', $str);
        $str= ltrim ($str, '_'); // fix for first char ending up being an underscore
        $str = strtolower($str);

        return $str;
    }

    /**
    * Converts a snake case string into camel case
    *
    * @param string $str String in snake case
    * @return string $str String converted into camel case
    */
    public static function snakeToCamel($str, $pascal_case=false) {
        $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $str)));
        if (!$pascal_case) {
            if (function_exists('lcfirst')) {
                return lcfirst($str);
            } else {
                return strtolower(substr($str, 0, 1)) . substr($str, 1);
            }
        }
        return $str;
    }

    public static function sanitizeClient($str, $allow_html=false) {
        if (!$allow_html) {
            return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
        } else {
            throw new Exception(__FUNCTION__ . '() → allow_html not implemented');
        }
    }

    public static function sanitizeXml($str) {
        $str = str_replace('&amp;', '&', $str);
        $str = str_replace('&lt;', '<', $str);

        $str = str_replace('&', '&amp;', $str);
        $str = str_replace('<', '&lt;', $str);

        return $str;
    }

    public static function strIsInt($str) {
        return preg_match("/^-?[0-9]+$/", $str);
    }

    public static function strIsFloat($str) {
        if ($str === '') {
            return false;
        }

        return ($str == (string)(float)$str);
    }

    public static function strIsDate($value, $format = 'mm/dd/yyyy') {
        if (strlen($value) >= 6 && strlen($format) == 10) {
            // find separator. Remove all other characters from $format
            $separator_only = str_replace(array('m', 'd', 'y'),'', $format);
            $separator = $separator_only[0]; // separator is first character

            if ($separator && strlen($separator_only) == 2) {
                // make regex
                $regexp = str_replace('mm', '(0?[1-9]|1[0-2])', $format);
                $regexp = str_replace('dd', '(0?[1-9]|[1-2][0-9]|3[0-1])', $regexp);
                $regexp = str_replace('yyyy', '(19|20)?[0-9][0-9]', $regexp);
                $regexp = str_replace($separator, "\\" . $separator, $regexp);
                if ($regexp != $value && preg_match('/'.$regexp.'\z/', $value)) {
                    // check date
                    $arr = explode($separator,$value);
                    $day = $arr[0];
                    $month = $arr[1];
                    $year = $arr[2];
                    if (@checkdate($month, $day, $year))
                        return true;
                }
            }
        }
        return false;
    }

    public static function strIsEmail($str) {
        return (preg_match('/(@.*@)|(\.\.)|(@\.)|(\.@)|(^\.)/', $str) ||  preg_match('/^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?)$/', $str));
    }
}
?>
