<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function btn_achor($uri, $title = NULL, $attr = NULL)
{
    return anchor(site_url($uri), $title, $attr);
}

function btn_edit($uri, $title = NULL)
{
	return anchor($uri, '<i class="fa fa-edit"></i>' . $title, array(
		'class'=>'btn btn-primary',
	));
}

function btn_delete($uri, $title = NULL)
{
    return anchor($uri, '<i class="fa fa-times"></i>' . $title, array(
        'onclick'=>"return confirm('You are about to delete a record. This is cannot be undone. Are you sure?');",
        'class'=>'btn btn-danger'
    ));
}

function delete_link($uri, $title = NULL)
{
	return anchor($uri, '<i class="glyphicon glyphicon-trash"></i> ' . $title, array(
		'onclick'=>"return confirm('You are about to delete a record. This is cannot be undone. Are you sure?');",
        'class' => 'text-danger lead',
        'title' => 'Delete',
	));
}

function btn_detail($uri, $title = NULL)
{
    return anchor($uri, '<i class="fa fa-table"></i>' . $title, array(
        'class'=>'btn btn-info', 'title' => $title
    ));
}

function btn_excel($uri, $title = NULL)
{
    return anchor($uri, '<i class="fa fa-download"></i>' . $title, array(
        'class'=>'btn btn-success', 'title' => $title
    ));
}

function btn_new($uri, $title = NULL, $attr = NULL)
{
    if (is_array($attr))
        $attr = array_merge(array('class' => 'btn btn-danger'), $attr);
    else
        $attr = array('class' => 'btn btn-danger');

    return anchor($uri, '<i class="fa fa-plus"></i> ' . $title, $attr);
}

/**
 * Dump helper. Functions to dump variables to the screen, in a nicley formatted manner.
 * @author Joost van Veen
 * @version 1.0
 */
if (!function_exists('dump')) {
    function dump ($var, $label = 'Dump', $echo = TRUE)
    {
        // Store dump in variable
        ob_start();
        var_dump($var);
        $output = ob_get_clean();

        // Add formatting
        $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
        $output = '<pre style="background: #FFFEEF; color: #000; border: 1px dotted #000; padding: 10px; margin: 10px 0; text-align: left;">' . $label . ' => ' . $output . '</pre>';

        // Output
        if ($echo == TRUE) {
            echo $output;
        }
        else {
            return $output;
        }
    }
}


if (!function_exists('dump_exit')) {
    function dump_exit($var, $label = 'Dump', $echo = TRUE) {
        dump ($var, $label, $echo);
        exit;
    }
}

function decimal($str)
{
    return (bool)preg_match('/^[\-+]?[0-9]+\.[0-9]+$/', $str);
}

function date_convert_to_mysql($date, $format = "Y-m-d")
{
    // Now convert the date field(s)
    $date = date($format, strtotime($date));
    return $date;
}

function date_convert_to_php($date, $format = "m-d-Y")
{
    // Now convert the date field(s)
    $date = date($format, strtotime($date));
    return $date;
}

function to_decimal($str)
{
    return str_replace(',', '', $str);
}

function to_negative($num)
{
    return -1 * abs($num);
}

function t($str)
{
    return strtoupper($str);
}

function nf($str, $decimal_places = DECIMAL_PLACES)
{
    return number_format($str, $decimal_places, '.', ',');
    // return floor(($str*100))/100;
}

function is_graded($is_graded)
{
    return ($is_graded == 1) ? "class='success'" : "class='info'";
}


/**
 * Return the value for a key in an array or a property in an object.
 * Typical usage:
 *
 * $object->foo = 'Bar';
 * echo get_key($object, 'foo');
 *
 * $array['baz'] = 'Bat';
 * echo get_key($array, 'baz');
 *
 * @param mixed $haystack
 * @param string $needle
 * @param mixed $default_value The value if key could not be found.
 * @return mixed
 */
function get_key ($haystack, $needle, $default_value = '')
{
    if (is_array($haystack)) {
        // We have an array. Find the key.
        return isset($haystack[$needle]) ? $haystack[$needle] : $default_value;
    }
    else {
        // If it's not an array it must be an object
        return isset($haystack->$needle) ? $haystack->$needle : $default_value;
    }
}


function convert_number_to_words($number) {

    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'fourty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninety',
        100                 => 'hundred',
        1000                => 'thousand',
        1000000             => 'million',
        1000000000          => 'billion',
        1000000000000       => 'trillion',
        1000000000000000    => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}


/**
 * Return value without knowing key in one-pair-associative-aray
 */
function get_array_value_without_key($array)
{
    return end( $array);
}


/**
 * Return grading system format
 */
 // function grading_sys($leclab = 0)
 // {
 //   switch ($leclab) {
 //     case 1:
 //
 //       break;
 //
 //     default:
 //       # code...
 //       break;
 //   }
 //
 //   return $input;
 // }



 /**
  * This file is part of the array_column library
  *
  * For the full copyright and license information, please view the LICENSE
  * file that was distributed with this source code.
  *
  * @copyright Copyright (c) Ben Ramsey (http://benramsey.com)
  * @license http://opensource.org/licenses/MIT MIT
  */
 if (!function_exists('array_column')) {
     /**
      * Returns the values from a single column of the input array, identified by
      * the $columnKey.
      *
      * Optionally, you may provide an $indexKey to index the values in the returned
      * array by the values from the $indexKey column in the input array.
      *
      * @param array $input A multi-dimensional array (record set) from which to pull
      *                     a column of values.
      * @param mixed $columnKey The column of values to return. This value may be the
      *                         integer key of the column you wish to retrieve, or it
      *                         may be the string key name for an associative array.
      * @param mixed $indexKey (Optional.) The column to use as the index/keys for
      *                        the returned array. This value may be the integer key
      *                        of the column, or it may be the string key name.
      * @return array
      */
     function array_column($input = null, $columnKey = null, $indexKey = null)
     {
         // Using func_get_args() in order to check for proper number of
         // parameters and trigger errors exactly as the built-in array_column()
         // does in PHP 5.5.
         $argc = func_num_args();
         $params = func_get_args();
         if ($argc < 2) {
             trigger_error("array_column() expects at least 2 parameters, {$argc} given", E_USER_WARNING);
             return null;
         }
         if (!is_array($params[0])) {
             trigger_error(
                 'array_column() expects parameter 1 to be array, ' . gettype($params[0]) . ' given',
                 E_USER_WARNING
             );
             return null;
         }
         if (!is_int($params[1])
             && !is_float($params[1])
             && !is_string($params[1])
             && $params[1] !== null
             && !(is_object($params[1]) && method_exists($params[1], '__toString'))
         ) {
             trigger_error('array_column(): The column key should be either a string or an integer', E_USER_WARNING);
             return false;
         }
         if (isset($params[2])
             && !is_int($params[2])
             && !is_float($params[2])
             && !is_string($params[2])
             && !(is_object($params[2]) && method_exists($params[2], '__toString'))
         ) {
             trigger_error('array_column(): The index key should be either a string or an integer', E_USER_WARNING);
             return false;
         }
         $paramsInput = $params[0];
         $paramsColumnKey = ($params[1] !== null) ? (string) $params[1] : null;
         $paramsIndexKey = null;
         if (isset($params[2])) {
             if (is_float($params[2]) || is_int($params[2])) {
                 $paramsIndexKey = (int) $params[2];
             } else {
                 $paramsIndexKey = (string) $params[2];
             }
         }
         $resultArray = array();
         foreach ($paramsInput as $row) {
             $key = $value = null;
             $keySet = $valueSet = false;
             if ($paramsIndexKey !== null && array_key_exists($paramsIndexKey, $row)) {
                 $keySet = true;
                 $key = (string) $row[$paramsIndexKey];
             }
             if ($paramsColumnKey === null) {
                 $valueSet = true;
                 $value = $row;
             } elseif (is_array($row) && array_key_exists($paramsColumnKey, $row)) {
                 $valueSet = true;
                 $value = $row[$paramsColumnKey];
             }
             if ($valueSet) {
                 if ($keySet) {
                     $resultArray[$key] = $value;
                 } else {
                     $resultArray[] = $value;
                 }
             }
         }
         return $resultArray;
     }
}
