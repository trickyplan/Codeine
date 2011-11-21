<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::Fn ('Get', function ($Call)
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

    self::Fn ('Set', function ($Call)
        {
            setcookie ('Session', $Call['Session'], (time() + 187600), '/', null, false, true); // OPTME!
            setcookie ('Seal', $Call['Seal'], (time() + 187600), '/', null, false, true); // OPTME!

            return $Call;
        });


    self::Fn('Annulate', function ($Call)
    {
        setcookie ('Session', ''); // OPTME!
        setcookie ('Seal', ''); // OPTME!

        return $Call;
    });