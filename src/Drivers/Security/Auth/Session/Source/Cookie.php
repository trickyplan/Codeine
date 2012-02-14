<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.1
     */

    self::setFn ('Get', function ($Call)
        {
            if (isset($_COOKIE['Session']) && isset($_COOKIE['Seal']))
            {
                $Call['Auth']['Session'] = $_COOKIE['Session'];
                $Call['Auth']['Seal']    = $_COOKIE['Seal'];
            }
            else
                $Call['Auth'] = null;
 
            return $Call;
        });

    self::setFn ('Set', function ($Call)
        {
            setcookie ('Session', $Call['Session'], (time() + 187600), '/', null, false, true); // OPTME!
            setcookie ('Seal', $Call['Seal'], (time() + 187600), '/', null, false, true); // OPTME!

            return $Call;
        });


    self::setFn('Annulate', function ($Call)
    {
        setcookie ('Session', ''); // OPTME!
        setcookie ('Seal', ''); // OPTME!

        return $Call;
    });