<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

    function kisalt($kelime, $str = 10)
    {
        $kelime = strip_tags($kelime);
        if (strlen($kelime) > $str)
        {
            if (function_exists("mb_substr"))
                $kelime = mb_substr($kelime, 0, $str, "UTF-8").'...';
            else
                $kelime = substr($kelime, 0, $str).'...';
        }
        return $kelime;
    }

    function convertArray($arr)
    {
        if(!is_array($arr)) 
        $arr=array($arr);

        return $arr;
    }

?>