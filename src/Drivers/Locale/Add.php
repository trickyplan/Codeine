<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Language = F::Run('System.Interface.Web', 'DetectUALanguage');

        list($Locale, $Token) = explode(':', $Call['Request']['Token']);

        $Slices = explode('.', $Locale);

        $ID = array_pop($Slices);
        $Asset = implode('.', $Slices);

        $Locale = F::Run('IO', 'Read',
                    array (
                          'Storage' => 'Locale',
                          'Scope'   => $Asset.'/Locale/'.$Language,
                          'Where'   => $ID
                    ))[0];


        $Locale = F::Dot($Locale, $Token, $Call['Request']['Translation']);

        if ($Locale === null)
            $Locale = [$Token => $Call['Request']['Translation']];

        if (null !== F::Run('IO', 'Write',
                    array (
                          'Storage' => 'Locale',
                          'Scope'   => $Asset.'/Locale/'.$Language,
                          'Where'   => $ID,
                          'Data' => $Locale
                    )))
            $Call['Output']['Content'] = true;
        else
            $Call['Output']['Content'] = false;

        $Call['Renderer'] = 'View.JSON';

        return $Call;
    });