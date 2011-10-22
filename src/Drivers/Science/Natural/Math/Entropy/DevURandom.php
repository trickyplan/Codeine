<?php

    /* Codeine
     * @author BreathLess
     * @description: /dev/urandom command wrapper
     * @package Codeine
     * @version 6.0
     * @origin http://caveofchaos.com/blogs/view/generate-random-number-with-php-using-dev-urandom
     * @date 02.12.10
     * @time 0:00
     */

    self::Fn('Get', function ($Call)
    {
        $bits = '';
        $fp = fopen('/dev/urandom','rb');

        if ($fp !== FALSE)
        {
            $bits .= fread($fp,1);
            fclose($fp);
        }
        
        $bitlength = strlen($bits);
        
        for ($i = 0; $i < $bitlength; $i++)
            $int =  1+(ord($bits[$i]));

        return $int;
    });
