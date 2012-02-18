<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 7.1
     * @date 13.08.11
     * @time 22:37
     */

    /*
     * 'name' => string 'Edu.png' (length=7)
          'type' => string 'image/png' (length=9)
          'tmp_name' => string '/tmp/phpmoEH3Y' (length=14)
          'error' => int 0
          'size' => int 26211
     */
    self::setFn ('Write', function ($Call)
    {
        $Name = F::Live($Call['Naming'], $Call['Data'][$Call['Node']]);

        move_uploaded_file($Call['Data'][$Call['Node']]['tmp_name'], Root.'/'.$Call['Directory'].'/'. $Call['Scope'].'/'.$Name);

        return $Name;
    });