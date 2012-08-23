<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Do', function ($Call)
    {
        $Language = F::Run('System.Interface.Web', 'DetectUALanguage');

        $Tokens = explode('.', $Call['Request']['Token']);
        $Asset = array_shift($Tokens);

        $Locale = F::Run('IO', 'Read',
                    array (
                          'Storage' => 'Locale',
                          'Scope'   => $Asset.'/Locale/'.$Language,
                          'Where'   => $Asset
                    ))[0];


        $Locale = F::Dot($Locale, $Call['Request']['Token'], $Call['Request']['Translation']);

        F::Run('IO', 'Write',
                    array (
                          'Storage' => 'Locale',
                          'Scope'   => $Asset.'/Locale/'.$Language,
                          'Where'   => $Asset,
                          'Data' => $Locale
                    ))[0];

        $Call['Renderer'] = 'View.JSON';

        $Call['Output'] = true;
        return $Call;
    });