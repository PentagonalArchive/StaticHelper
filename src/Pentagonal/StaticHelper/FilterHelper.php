<?php
namespace Pentagonal\StaticHelper;

/**
 * Class FilterHelper
 *
 * @package Pentagonal\StaticHelper
 */
class FilterHelper
{
    /**
     * @var array
     */
    public static $allowedentitynames = array(
        'nbsp',    'iexcl',  'cent',    'pound',  'curren', 'yen',
        'brvbar',  'sect',   'uml',     'copy',   'ordf',   'laquo',
        'not',     'shy',    'reg',     'macr',   'deg',    'plusmn',
        'acute',   'micro',  'para',    'middot', 'cedil',  'ordm',
        'raquo',   'iquest', 'Agrave',  'Aacute', 'Acirc',  'Atilde',
        'Auml',    'Aring',  'AElig',   'Ccedil', 'Egrave', 'Eacute',
        'Ecirc',   'Euml',   'Igrave',  'Iacute', 'Icirc',  'Iuml',
        'ETH',     'Ntilde', 'Ograve',  'Oacute', 'Ocirc',  'Otilde',
        'Ouml',    'times',  'Oslash',  'Ugrave', 'Uacute', 'Ucirc',
        'Uuml',    'Yacute', 'THORN',   'szlig',  'agrave', 'aacute',
        'acirc',   'atilde', 'auml',    'aring',  'aelig',  'ccedil',
        'egrave',  'eacute', 'ecirc',   'euml',   'igrave', 'iacute',
        'icirc',   'iuml',   'eth',     'ntilde', 'ograve', 'oacute',
        'ocirc',   'otilde', 'ouml',    'divide', 'oslash', 'ugrave',
        'uacute',  'ucirc',  'uuml',    'yacute', 'thorn',  'yuml',
        'quot',    'amp',    'lt',      'gt',     'apos',   'OElig',
        'oelig',   'Scaron', 'scaron',  'Yuml',   'circ',   'tilde',
        'ensp',    'emsp',   'thinsp',  'zwnj',   'zwj',    'lrm',
        'rlm',     'ndash',  'mdash',   'lsquo',  'rsquo',  'sbquo',
        'ldquo',   'rdquo',  'bdquo',   'dagger', 'Dagger', 'permil',
        'lsaquo',  'rsaquo', 'euro',    'fnof',   'Alpha',  'Beta',
        'Gamma',   'Delta',  'Epsilon', 'Zeta',   'Eta',    'Theta',
        'Iota',    'Kappa',  'Lambda',  'Mu',     'Nu',     'Xi',
        'Omicron', 'Pi',     'Rho',     'Sigma',  'Tau',    'Upsilon',
        'Phi',     'Chi',    'Psi',     'Omega',  'alpha',  'beta',
        'gamma',   'delta',  'epsilon', 'zeta',   'eta',    'theta',
        'iota',    'kappa',  'lambda',  'mu',     'nu',     'xi',
        'omicron', 'pi',     'rho',     'sigmaf', 'sigma',  'tau',
        'upsilon', 'phi',    'chi',     'psi',    'omega',  'thetasym',
        'upsih',   'piv',    'bull',    'hellip', 'prime',  'Prime',
        'oline',   'frasl',  'weierp',  'image',  'real',   'trade',
        'alefsym', 'larr',   'uarr',    'rarr',   'darr',   'harr',
        'crarr',   'lArr',   'uArr',    'rArr',   'dArr',   'hArr',
        'forall',  'part',   'exist',   'empty',  'nabla',  'isin',
        'notin',   'ni',     'prod',    'sum',    'minus',  'lowast',
        'radic',   'prop',   'infin',   'ang',    'and',    'or',
        'cap',     'cup',    'int',     'sim',    'cong',   'asymp',
        'ne',      'equiv',  'le',      'ge',     'sub',    'sup',
        'nsub',    'sube',   'supe',    'oplus',  'otimes', 'perp',
        'sdot',    'lceil',  'rceil',   'lfloor', 'rfloor', 'lang',
        'rang',    'loz',    'spades',  'clubs',  'hearts', 'diams',
        'sup1',    'sup2',   'sup3',    'frac14', 'frac12', 'frac34',
        'there4',
    );

    /**
     * Add leading zeros when necessary.
     *
     * If you set the threshold to '4' and the number is '10', then you will get
     * back '0010'. If you set the threshold to '4' and the number is '5000', then you
     * will get back '5000'.
     *
     * Uses sprintf to append the amount of zeros based on the $threshold parameter
     * and the size of the number. If the number is large enough, then no zeros will
     * be appended.
     *
     *
     * @param mixed $number Number to append zeros to if not greater than threshold.
     * @param int   $threshold Digit places number needs to be to not have zeros added.
     * @return string Adds leading zeros to number if needed.
     */
    public static function zeroise($number, $threshold)
    {
        return sprintf('%0'.$threshold.'s', $number);
    }

    /**
     * Converts email addresses characters to HTML entities to block spam bots.
     *
     * @license followed WordPress license
     *
     * @param mixed $email_address Email address.
     * @param int $hex_encoding Optional. Set to 1 to enable hex encoding.
     * @return string Converted email address.
     */
    public static function antiSpamBot($email_address, $hex_encoding = 0)
    {
        if (is_array($email_address)) {
            foreach ($email_address as $key => $value) {
                $email_address[$key] = self::antiSpamBot($value, $hex_encoding);
            }
            return $email_address;
        }
        if (is_object($email_address)) {
            foreach (get_object_vars($email_address) as $key => $value) {
                $email_address->{$key} = self::antiSpamBot($value, $hex_encoding);
            }
            return $email_address;
        }
        $email_no_spam_address = '';
        for ($i = 0, $len = strlen($email_address); $i < $len; $i++) {
            $j = rand(0, 1 + $hex_encoding);
            if ($j == 0) {
                $email_no_spam_address .= '&#' . ord($email_address[$i]) . ';';
            } elseif ($j == 1) {
                $email_no_spam_address .= $email_address[$i];
            } elseif ($j == 2) {
                $email_no_spam_address .= '%' . static::zeroise(dechex(ord($email_address[$i])), 2);
            }
        }

        $email_no_spam_address = str_replace('@', '&#64;', $email_no_spam_address);

        return $email_no_spam_address;
    }

    /**
     * Entities the Multibytes string
     *
     * @param string $string the string to detect multibytes
     * @param boolean $entities  true if want to entity the output
     *
     * @return string
     */
    public static function multibyteEntities($string, $entities = true)
    {
        static $iconv = null;
        if (!isset($iconv)) {
            // safe resouce check
            $iconv = function_exists('iconv');
        }

        if (is_array($string)) {
            foreach ($string as $key => $value) {
                $string[$key] = self::multibyteEntities($value, $entities);
            }

            return $string;
        }

        if (is_object($string)) {
            foreach (get_object_vars($string) as $key => $value) {
                $string->{$key} = self::multibyteEntities($value, $entities);
            }

            return $string;
        }

        if (!$iconv) { // add \n\r\t as ASCII [ || ! preg_match("/[^\x20-\x7f\s]/", $string)]
            return $entities ? htmlentities(html_entity_decode($string)) : $string;
        }
        /**
         * Work Safe with Parse 4096 Bit | 4KB data split for regex callback & safe memory usage
         * that maybe fail on very long string
         */
        if (strlen($string) >= 4096) {
            return implode('', self::multibyteEntities(str_split($string, 4096), $entities));
        }

        return preg_replace_callback('/[\x{80}-\x{10FFFF}]/u', function ($m) {
            $char = current($m);
            $utf  = iconv('UTF-8', 'UCS-4//IGNORE', $char);
            return sprintf("&#x%s;", ltrim(strtolower(bin2hex($utf)), "0"));
        }, ($entities ? htmlentities(html_entity_decode($string)) : $string));
    }

    /**
     * Balances tags of string using a modified stack.
     *
     * @author Leonard Lin <leonard@acm.org>
     * @license GPL
     * @copyright November 4, 2001
     * @version 1.1
     * @todo Make better - change loop condition to $text in 1.2
     *
     * Modified by Scott Reilly (coffee2code) 02 Aug 2004
     *      1.1  Fixed handling of append/stack pop order of end text
     *           Added Cleaning Hooks
     *      1.0  First Version
     *
     * @param string $text Text to be balanced.
     * @return string Balanced text.
     *
     * Custom mods to be fixed to handle by system result output
     */
    public static function forceBalanceTags($text)
    {
        $tagstack = array();
        $stacksize = 0;
        $tagqueue = '';
        $newtext = '';
        // Known single-entity/self-closing tags
        $single_tags = array(
            'area', 'base', 'basefont', 'br', 'col',
            'command', 'embed', 'frame', 'hr', 'img',
            'input', 'isindex', 'link', 'meta', 'param', 'source'
        );
        $single_tags_2 = array(
            'img', 'meta', 'link', 'input'
        );
        // Tags that can be immediately nested within themselves
        $nestable_tags = array('blockquote', 'div', 'object', 'q', 'span');
        // check if contains <html> tag and split it
        // fix doctype
        $text = preg_replace('/<(\s+)?!(\s+)?(DOCTYPE)/i', '<!$3', $text);
        $rand = '%%_tmp_!_09876543211234567890_tmp';
        $text = str_replace('<!', '< '.$rand, $text);
        // bug fix for comments - in case you REALLY meant to type '< !--'
        $text = str_replace('< !--', '<    !--', $text);
        // bug fix for LOVE <3 (and other situations with '<' before a number)
        $text = preg_replace('#<([0-9]{1})#', '&lt;$1', $text);

        while (preg_match("/<((?!(?:\s+)".preg_quote($rand, '/').")\/?[\w:]*)\s*([^>]*)>/", $text, $regex)) {
            $newtext .= $tagqueue;
            $i = strpos($text, $regex[0]);
            $l = strlen($regex[0]);

            // clear the shifter
            $tagqueue = '';
            // Pop or Push
            if (isset($regex[1][0]) && '/' == $regex[1][0]) { // End Tag
                $tag = strtolower(substr($regex[1], 1));
                // if too many closing tags
                if ($stacksize <= 0) {
                    $tag = '';
                    // or close to be safe $tag = '/' . $tag;
                } elseif ($tagstack[$stacksize - 1] == $tag) {
                    // if stacktop value = tag close value then pop
                    // found closing tag
                    $tag = '</' . $tag . '>'; // Close Tag
                    // Pop
                    array_pop($tagstack);
                    $stacksize--;
                } else { // closing tag not at top, search for it
                    for ($j = $stacksize-1; $j >= 0; $j--) {
                        if ($tagstack[$j] == $tag) {
                            // add tag to tagqueue
                            for ($k = $stacksize-1; $k >= $j; $k--) {
                                $tagqueue .= '</' . array_pop($tagstack) . '>';
                                $stacksize--;
                            }
                            break;
                        }
                    }
                    $tag = '';
                }
            } else { // Begin Tag
                $tag = strtolower($regex[1]);

                // Tag Cleaning

                // If it's an empty tag "< >", do nothing
                if ('' == $tag) {
                    // do nothing
                } elseif (substr($regex[2], -1) == '/') {
                    // ElseIf it presents itself as a self-closing tag...
                    // ----
                    // ...but it isn't a known single-entity self-closing tag, then don't let it be treated as such and
                    // immediately close it with a closing tag (the tag will encapsulate no text as a result)
                    if (! in_array($tag, $single_tags)) {
                        $regex[2] = trim(substr($regex[2], 0, -1)) . "></$tag";
                    }
                } elseif (in_array($tag, $single_tags_2)) {
                    // ElseIf it's a known single-entity tag but it doesn't close itself, do so
                    // $regex[2] .= '';
                } elseif (in_array($tag, $single_tags)) {
                    // ElseIf it's a known single-entity tag but it doesn't close itself, do so
                    $regex[2] .= '/';
                } else {
                    // Else it's not a single-entity tag
                    // ---------
                    // If the top of the stack is the same as the tag we want to push, close previous tag
                    if ($stacksize > 0 && !in_array($tag, $nestable_tags) && $tagstack[$stacksize - 1] == $tag) {
                        $tagqueue = '</' . array_pop($tagstack) . '>';
                        $stacksize--;
                    }
                    $stacksize = array_push($tagstack, $tag);
                }

                // Attributes
                $attributes = $regex[2];
                if (! empty($attributes) && $attributes[0] != '>') {
                    $attributes = ' ' . $attributes;
                }

                $tag = '<' . $tag . $attributes . '>';
                //If already queuing a close tag, then put this tag on, too
                if (! empty($tagqueue)) {
                    $tagqueue .= $tag;
                    $tag = '';
                }
            }

            $newtext .= substr($text, 0, $i) . $tag;
            $text = substr($text, $i + $l);
        }

        // Clear Tag Queue
        $newtext .= $tagqueue;

        // Add Remaining text
        $newtext .= $text;
        unset($text); // freed memory
        // Empty Stack
        while ($x = array_pop($tagstack)) {
            $newtext .= '</' . $x . '>'; // Add remaining tags to close
        }
        // fix for the bug with HTML comments
        $newtext = str_replace("< {$rand}", "<!", $newtext);
        $newtext = str_replace("< !--", "<!--", $newtext);
        $newtext = str_replace("<    !--", "< !--", $newtext);
        return $newtext;
    }

    /**
     * Checks to see if a string is utf8 encoded.
     *
     * NOTE: This function checks for 5-Byte sequences, UTF8
     *       has Bytes Sequences with a maximum length of 4.
     *
     * @author bmorel at ssi dot fr (modified)
     *
     * @param  string $str The string to be checked
     * @return bool   true if $str fits a UTF-8 model, false otherwise.
     */
    public static function seemsUtf8($str)
    {
        if (is_string($str) || is_numeric($str)) {
            $str    = (string) $str;
            $length = strlen($str);
            for ($i=0; $i < $length; $i++) {
                $c = ord($str[$i]);
                if ($c < 0x80) {
                    $n = 0; # 0bbbbbbb
                } elseif (($c & 0xE0) == 0xC0) {
                    $n=1; # 110bbbbb
                } elseif (($c & 0xF0) == 0xE0) {
                    $n=2; # 1110bbbb
                } elseif (($c & 0xF8) == 0xF0) {
                    $n=3; # 11110bbb
                } elseif (($c & 0xFC) == 0xF8) {
                    $n=4; # 111110bb
                } elseif (($c & 0xFE) == 0xFC) {
                    $n=5; # 1111110b
                } else {
                    return false; # Does not match any model
                }
                # n bytes matching 10bbbbbb follow ?
                for ($j = 0; $j < $n; $j++) {
                    if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80)) {
                        return false;
                    }
                }
            }
            return true;
        }

        return false;
    }

    /**
     * Converts all accent characters to ASCII characters.
     *     # Nested Removeaccents #
     * If there are no accent characters, then the string given is just returned.
     *
     * @license followed WordPress license
     *
     * @param  string $string Text that might have accent characters
     * @return string Filtered string with replaced "nice" characters.
     */
    public static function removeAccents($string)
    {
        if (is_array($string)) {
            foreach ($string as $key => $value) {
                $string[$key] = self::removeAccents($string);
            }
            return $string;
        }
        if (is_object($string)) {
            foreach (get_object_vars($string) as $key => $value) {
                $string[$key] = self::removeAccents($string);
            }
            return $string;
        }

        if (!preg_match('/[\x80-\xff]/', $string)) {
            return $string;
        }

        if (static::seemsUtf8($string)) {
            $chars = array(
                // Decompositions for Latin-1 Supplement
                chr(194).chr(170) => 'a', chr(194).chr(186) => 'o',
                chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
                chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
                chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
                chr(195).chr(134) => 'AE',chr(195).chr(135) => 'C',
                chr(195).chr(136) => 'E', chr(195).chr(137) => 'E',
                chr(195).chr(138) => 'E', chr(195).chr(139) => 'E',
                chr(195).chr(140) => 'I', chr(195).chr(141) => 'I',
                chr(195).chr(142) => 'I', chr(195).chr(143) => 'I',
                chr(195).chr(144) => 'D', chr(195).chr(145) => 'N',
                chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
                chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
                chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
                chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
                chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
                chr(195).chr(158) => 'TH',chr(195).chr(159) => 's',
                chr(195).chr(160) => 'a', chr(195).chr(161) => 'a',
                chr(195).chr(162) => 'a', chr(195).chr(163) => 'a',
                chr(195).chr(164) => 'a', chr(195).chr(165) => 'a',
                chr(195).chr(166) => 'ae',chr(195).chr(167) => 'c',
                chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
                chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
                chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
                chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
                chr(195).chr(176) => 'd', chr(195).chr(177) => 'n',
                chr(195).chr(178) => 'o', chr(195).chr(179) => 'o',
                chr(195).chr(180) => 'o', chr(195).chr(181) => 'o',
                chr(195).chr(182) => 'o', chr(195).chr(184) => 'o',
                chr(195).chr(185) => 'u', chr(195).chr(186) => 'u',
                chr(195).chr(187) => 'u', chr(195).chr(188) => 'u',
                chr(195).chr(189) => 'y', chr(195).chr(190) => 'th',
                chr(195).chr(191) => 'y', chr(195).chr(152) => 'O',
                // Decompositions for Latin Extended-A
                chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
                chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
                chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
                chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
                chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
                chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
                chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
                chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
                chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
                chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
                chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
                chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
                chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
                chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
                chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
                chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
                chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
                chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
                chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
                chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
                chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
                chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
                chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
                chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
                chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
                chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
                chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
                chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
                chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
                chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
                chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
                chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
                chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
                chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
                chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
                chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
                chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
                chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
                chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
                chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
                chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
                chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
                chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
                chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
                chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
                chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
                chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
                chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
                chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
                chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
                chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
                chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
                chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
                chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
                chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
                chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
                chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
                chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
                chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
                chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
                chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
                chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
                chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
                chr(197).chr(190) => 'z', chr(197).chr(191) => 's',
                // Decompositions for Latin Extended-B
                chr(200).chr(152) => 'S', chr(200).chr(153) => 's',
                chr(200).chr(154) => 'T', chr(200).chr(155) => 't',
                // Euro Sign
                chr(226).chr(130).chr(172) => 'E',
                // GBP (Pound) Sign
                chr(194).chr(163) => '',
                // Vowels with diacritic (Vietnamese)
                // unmarked
                chr(198).chr(160) => 'O', chr(198).chr(161) => 'o',
                chr(198).chr(175) => 'U', chr(198).chr(176) => 'u',
                // grave accent
                chr(225).chr(186).chr(166) => 'A', chr(225).chr(186).chr(167) => 'a',
                chr(225).chr(186).chr(176) => 'A', chr(225).chr(186).chr(177) => 'a',
                chr(225).chr(187).chr(128) => 'E', chr(225).chr(187).chr(129) => 'e',
                chr(225).chr(187).chr(146) => 'O', chr(225).chr(187).chr(147) => 'o',
                chr(225).chr(187).chr(156) => 'O', chr(225).chr(187).chr(157) => 'o',
                chr(225).chr(187).chr(170) => 'U', chr(225).chr(187).chr(171) => 'u',
                chr(225).chr(187).chr(178) => 'Y', chr(225).chr(187).chr(179) => 'y',
                // hook
                chr(225).chr(186).chr(162) => 'A', chr(225).chr(186).chr(163) => 'a',
                chr(225).chr(186).chr(168) => 'A', chr(225).chr(186).chr(169) => 'a',
                chr(225).chr(186).chr(178) => 'A', chr(225).chr(186).chr(179) => 'a',
                chr(225).chr(186).chr(186) => 'E', chr(225).chr(186).chr(187) => 'e',
                chr(225).chr(187).chr(130) => 'E', chr(225).chr(187).chr(131) => 'e',
                chr(225).chr(187).chr(136) => 'I', chr(225).chr(187).chr(137) => 'i',
                chr(225).chr(187).chr(142) => 'O', chr(225).chr(187).chr(143) => 'o',
                chr(225).chr(187).chr(148) => 'O', chr(225).chr(187).chr(149) => 'o',
                chr(225).chr(187).chr(158) => 'O', chr(225).chr(187).chr(159) => 'o',
                chr(225).chr(187).chr(166) => 'U', chr(225).chr(187).chr(167) => 'u',
                chr(225).chr(187).chr(172) => 'U', chr(225).chr(187).chr(173) => 'u',
                chr(225).chr(187).chr(182) => 'Y', chr(225).chr(187).chr(183) => 'y',
                // tilde
                chr(225).chr(186).chr(170) => 'A', chr(225).chr(186).chr(171) => 'a',
                chr(225).chr(186).chr(180) => 'A', chr(225).chr(186).chr(181) => 'a',
                chr(225).chr(186).chr(188) => 'E', chr(225).chr(186).chr(189) => 'e',
                chr(225).chr(187).chr(132) => 'E', chr(225).chr(187).chr(133) => 'e',
                chr(225).chr(187).chr(150) => 'O', chr(225).chr(187).chr(151) => 'o',
                chr(225).chr(187).chr(160) => 'O', chr(225).chr(187).chr(161) => 'o',
                chr(225).chr(187).chr(174) => 'U', chr(225).chr(187).chr(175) => 'u',
                chr(225).chr(187).chr(184) => 'Y', chr(225).chr(187).chr(185) => 'y',
                // acute accent
                chr(225).chr(186).chr(164) => 'A', chr(225).chr(186).chr(165) => 'a',
                chr(225).chr(186).chr(174) => 'A', chr(225).chr(186).chr(175) => 'a',
                chr(225).chr(186).chr(190) => 'E', chr(225).chr(186).chr(191) => 'e',
                chr(225).chr(187).chr(144) => 'O', chr(225).chr(187).chr(145) => 'o',
                chr(225).chr(187).chr(154) => 'O', chr(225).chr(187).chr(155) => 'o',
                chr(225).chr(187).chr(168) => 'U', chr(225).chr(187).chr(169) => 'u',
                // dot below
                chr(225).chr(186).chr(160) => 'A', chr(225).chr(186).chr(161) => 'a',
                chr(225).chr(186).chr(172) => 'A', chr(225).chr(186).chr(173) => 'a',
                chr(225).chr(186).chr(182) => 'A', chr(225).chr(186).chr(183) => 'a',
                chr(225).chr(186).chr(184) => 'E', chr(225).chr(186).chr(185) => 'e',
                chr(225).chr(187).chr(134) => 'E', chr(225).chr(187).chr(135) => 'e',
                chr(225).chr(187).chr(138) => 'I', chr(225).chr(187).chr(139) => 'i',
                chr(225).chr(187).chr(140) => 'O', chr(225).chr(187).chr(141) => 'o',
                chr(225).chr(187).chr(152) => 'O', chr(225).chr(187).chr(153) => 'o',
                chr(225).chr(187).chr(162) => 'O', chr(225).chr(187).chr(163) => 'o',
                chr(225).chr(187).chr(164) => 'U', chr(225).chr(187).chr(165) => 'u',
                chr(225).chr(187).chr(176) => 'U', chr(225).chr(187).chr(177) => 'u',
                chr(225).chr(187).chr(180) => 'Y', chr(225).chr(187).chr(181) => 'y',
                // Vowels with diacritic (Chinese, Hanyu Pinyin)
                chr(201).chr(145) => 'a',
                // macron
                chr(199).chr(149) => 'U', chr(199).chr(150) => 'u',
                // acute accent
                chr(199).chr(151) => 'U', chr(199).chr(152) => 'u',
                // caron
                chr(199).chr(141) => 'A', chr(199).chr(142) => 'a',
                chr(199).chr(143) => 'I', chr(199).chr(144) => 'i',
                chr(199).chr(145) => 'O', chr(199).chr(146) => 'o',
                chr(199).chr(147) => 'U', chr(199).chr(148) => 'u',
                chr(199).chr(153) => 'U', chr(199).chr(154) => 'u',
                // grave accent
                chr(199).chr(155) => 'U', chr(199).chr(156) => 'u',
            );

            // Used for locale-specific rules
            // $locale = get_locale();

            // if ('de_DE' == $locale) {
            //  $chars[ chr(195).chr(132) ] = 'Ae';
            //  $chars[ chr(195).chr(164) ] = 'ae';
            //  $chars[ chr(195).chr(150) ] = 'Oe';
            //  $chars[ chr(195).chr(182) ] = 'oe';
            //  $chars[ chr(195).chr(156) ] = 'Ue';
            //  $chars[ chr(195).chr(188) ] = 'ue';
            //  $chars[ chr(195).chr(159) ] = 'ss';
            // } elseif ('da_DK' === $locale) {
            //  $chars[ chr(195).chr(134) ] = 'Ae';
            //      $chars[ chr(195).chr(166) ] = 'ae';
            //  $chars[ chr(195).chr(152) ] = 'Oe';
            //  $chars[ chr(195).chr(184) ] = 'oe';
            //  $chars[ chr(195).chr(133) ] = 'Aa';
            //  $chars[ chr(195).chr(165) ] = 'aa';
            // }

            $string = strtr($string, $chars);
        } else {
            // Assume ISO-8859-1 if not UTF-8
            $chars['in'] = chr(128).chr(131).chr(138).chr(142).chr(154).chr(158)
                .chr(159).chr(162).chr(165).chr(181).chr(192).chr(193).chr(194)
                .chr(195).chr(196).chr(197).chr(199).chr(200).chr(201).chr(202)
                .chr(203).chr(204).chr(205).chr(206).chr(207).chr(209).chr(210)
                .chr(211).chr(212).chr(213).chr(214).chr(216).chr(217).chr(218)
                .chr(219).chr(220).chr(221).chr(224).chr(225).chr(226).chr(227)
                .chr(228).chr(229).chr(231).chr(232).chr(233).chr(234).chr(235)
                .chr(236).chr(237).chr(238).chr(239).chr(241).chr(242).chr(243)
                .chr(244).chr(245).chr(246).chr(248).chr(249).chr(250).chr(251)
                .chr(252).chr(253).chr(255);

            $chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";

            $string = strtr($string, $chars['in'], $chars['out']);
            $double_chars['in'] = array(
                chr(140),
                chr(156),
                chr(198),
                chr(208),
                chr(222),
                chr(223),
                chr(230),
                chr(240),
                chr(254)
            );
            $double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
            $string = str_replace($double_chars['in'], $double_chars['out'], $string);
        }

        return $string;
    }

    public static function emailUTF8($email)
    {
        if (!is_string($email) || strpos($email, '.') == false || strpos($email, '@') == false) {
            return false;
        }

        $email   = trim(strtolower($email));
        $explode = explode('@', $email);
        // validate
        if (count($explode) <> 2 || strpos($explode[1], '.') === false || preg_match('/[^0-9a-z\.\-\_]/i', $explode[0])) {
            return false;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return $email;
    }

    public static function filterForUsername($username, $length = 64, $minimum = 3)
    {
        if (!is_string($username)) {
            return false;
        }
        $username = trim(strtolower($username));
        if (strlen($username) < 1) {
            return false;
        }

        $minimum = !is_numeric($minimum) ? 3 : abs(intval($minimum));
        $minimum = $length < 1 ? 1 : (
            $minimum > $length ? $length : $minimum
        );

        $length = !is_numeric($length) ? 40 : abs(intval($length));
        $length = $length < 1 ? 0 : $length;

        if (
            strlen($username) < $minimum
            || strlen($username) > $length
            // must be end with alpha numeric
            || $username[0] == '-' || $username[strlen($username)-1] == '-'
            // must be end with alpha numeric
            || $username[0] == '_' || $username[strlen($username)-1] == '_'
            || preg_match('/[^a-z0-9\_\-]/', $username)
            // must be have an alpha or numeric
            || ! preg_match('/[a-z0-9]/', $username)
        ) {
            return false;
        }

        return $username;
    }

    /**
     * Fix htmlentities for some usage
     *
     * @param string $str
     * @param null $quote_style
     * @param null $charset
     * @param bool $double_encode
     * @return mixed
     */
    public static function htmlentitiesFix($str, $quote_style = null, $charset = null, $double_encode = true)
    {
        $quote_style = $quote_style === null ? (ENT_COMPAT | ENT_HTML401) : $quote_style;
        $charset = $charset === null ? (ini_get("default_charset")) : $charset;
        $entities = htmlentities($str, $quote_style, $charset, $double_encode);
        // Change back the allowed entities in our entity whitelist
        $string = preg_replace_callback('/&amp;([A-Za-z]{2,8}[0-9]{0,2})(;|\s)/', function ($c) {
            $d = $c[2] == ';' ? ';' : ";{$c[2]}";
            return in_array($c[1], self::$allowedentitynames) ? "&{$c[1]}{$d}" : "&amp;$c[1]$c[2]";
        }, $entities);

        return $string;
    }

    /**
     * Fix htmlspecialchars function for some usage
     *
     * @param $str
     * @param null $quote_style
     * @param null $charset
     * @param bool $double_encode
     * @return mixed
     */
    public static function htmlspecialcharsFix($str, $quote_style = null, $charset = null, $double_encode = true)
    {
        $quote_style = $quote_style === null ? (ENT_COMPAT | ENT_HTML401) : $quote_style;
        $charset = $charset === null ? (ini_get("default_charset")) : $charset;
        $entities = htmlspecialchars($str, $quote_style, $charset, $double_encode);
        // Change back the allowed entities in our entity whitelist
        $string = preg_replace_callback('/&amp;([A-Za-z]{2,8}[0-9]{0,2})(;|\s)/', function ($c) {
            $d = $c[2] == ';' ? ';' : ";{$c[2]}";
            return in_array($c[1], self::$allowedentitynames) ? "&{$c[1]}{$d}" : "&amp;$c[1]$c[2]";
        }, $entities);

        return $string;
    }

    /**
     * Escaping or add backslash if has no escaped of single quote
     * @param  mixed    $string values to be escaped
     * @return mixed
     */
    public static function escapeSingleQuote($string)
    {
        if (is_array($string)) {
            foreach ($string as $key => $value) {
                $string[$key] = self::escapeSingleQuote($value);
            }
        } elseif (is_object($string)) {
            foreach (get_object_vars($string) as $key => $value) {
                $string->{$key} = self::escapeSingleQuote($value);
            }
        } elseif (is_string($string)) {
            $string = str_replace("\\\'", "\'", preg_replace('/\'/', "\'", $string));
        }

        return $string;
    }

    /**
     * Escaping or add backslash if has no escaped of double quote
     * @param  mixed    $string values to be escaped
     * @return mixed
     */
    public static function escapeDoubleQuote($string)
    {
        if (is_array($string)) {
            foreach ($string as $key => $value) {
                $string[$key] = self::escapeDoubleQuote($value);
            }
        } elseif (is_object($string)) {
            foreach (get_object_vars($string) as $key => $value) {
                $string->{$key} = self::escapeDoubleQuote($value);
            }
        } elseif (is_string($string)) {
            $string = str_replace('\\\"', '\"', preg_replace('/\"/', '\"', $string));
        }

        return $string;
    }

    /**
     * Escaping or add backslash if has no escaped of double & Single quote
     * @param  mixed    $string values to be escaped
     * @return mixed
     */
    public static function escapeQuote($string)
    {
        if (is_array($string)) {
            foreach ($string as $key => $value) {
                $string[$key] = self::escapeQuote($value);
            }
        } elseif (is_object($string)) {
            foreach (get_object_vars($string) as $key => $value) {
                $string->{$key} = self::escapeQuote($value);
            }
        } elseif (is_string($string)) {
            $string = str_replace(
                array('\\\"', "\\\'"),
                array('\"', "\'"),
                preg_replace('/(\"|\')/', '\$1', $string)
            );
        }

        return $string;
    }

    /**
     * Set cookie domain with .domain.ext for multi sub domain
     *
     * @param  string  $domain
     * @return string $return domain ( .domain.com )
     */
    public static function splitCrossDomain($domain)
    {
        // domain must be string
        if (! is_string($domain)) {
            return $domain;
        }

        // make it domain lower
        $domain = strtolower($domain);
        $domain = preg_replace('/((http|ftp)s?|sftp|xmp):\/\//i', '', $domain);
        $domain = preg_replace('/\/.*$/', '', $domain);
        $is_ip = filter_var($domain, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
        if (!$is_ip) {
            $is_ip = filter_var($domain, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
        }
        if (!$is_ip) {
            $parse  = parse_url('http://'.$domain.'/');
            $domain = isset($parse['host']) ? $parse['host'] : null;
            if ($domain === null) {
                return null;
            }
        }
        if (!preg_match('/^((\[[0-9a-f:]+\])|(\d{1,3}(\.\d{1,3}){3})|[a-z0-9\-\.]+)(:\d+)?$/i', $domain)
            || $is_ip
            || $domain == '127.0.0.1'
            || $domain == 'localhost'
        ) {
                return $domain;
            }

        $domain = preg_replace('/[~!@#$%^&*()+`\{\}\]\[\/\\\'\;\<\>\,\"\?\=\|]/', '', $domain);
        if (strpos($domain, '.') !== false) {
            if (preg_match('/(.*\.)+(.*\.)+(.*)/', $domain)) {
                $return     = '.'.preg_replace('/(.*\.)+(.*\.)+(.*)/', '$2$3', $domain);
            } else {
                $return = '.'.$domain;
            }
        } else {
            $return = $domain;
        }
        return $return;
    }
}
