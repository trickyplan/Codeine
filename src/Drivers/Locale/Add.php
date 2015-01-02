<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        list($Scope, $Token) = explode(':', $Call['Request']['Token']);

        $Slices = explode('.', $Scope);

        $ID = array_pop($Slices);
        $Asset = implode('/', $Slices);

        $Tokens = F::Run('IO', 'Read',
                    [
                          'Storage' => 'Locale',
                          'Scope'   => $Asset.'/Locale/'.$Call['Locale'],
                          'Where'   => $ID
                    ])[0];

        if ($Tokens === null)
            $Tokens = [$Token => $Call['Request']['Translation']];
        else
            $Tokens = F::Dot($Tokens, $Token, $Call['Request']['Translation']);

        ksort($Tokens);

        if (null !== F::Run('IO', 'Write',
                    [
                          'Storage' => 'Locale',
                          'Scope'   => $Asset.'/Locale/'.$Call['Locale'],
                          'Where'   => ['ID' => $ID],
                          'Data' => $Tokens
                    ]))

            $Call['Output']['Content'] = true;
        else
            $Call['Output']['Content'] = false;

        return $Call;
    });