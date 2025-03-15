<?php 

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

?>