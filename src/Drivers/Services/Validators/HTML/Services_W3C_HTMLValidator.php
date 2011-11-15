<?php

   /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    require_once 'Services/W3C/HTMLValidator.php';

    self::Fn ('URI', function ($Call)
    {
        $Validator = new Services_W3C_HTMLValidator();
        $Result = $Validator->validate($Call['URL']);
        return (array)$Result;
    });

    self::Fn ('Code', function ($Call)
    {
        $Validator = new Services_W3C_HTMLValidator();
        $Result = $Validator->validateFragment($Call['Value']);

        return (array)$Result;
    });

    self::Fn ('File', function ($Call)
    {
        $Validator = new Services_W3C_HTMLValidator();
        $Result = $Validator->validateFragmentFile($Call['File']);
        return (array)$Result;
    });