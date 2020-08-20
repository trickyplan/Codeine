<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Output']['Content'] = false;

        if (isset($Call['Request']['Token']))
        {
            list($Scope, $Token) = explode(':', $Call['Request']['Token']);

            $Slices = explode('.', $Scope);

            $ID = array_pop($Slices);
            $Asset = implode('/', $Slices);

            $Tokens = F::Run('IO', 'Read',
                        [
                            'Storage'   => 'Locale',
                            'Scope'     => $Asset.'/Locale/'.$Call['Locale'],
                            'Where'     => $ID,
                            'Time'      => microtime(true),
                            'One'    => true
                        ]);

            if (empty($Tokens))
                ;
            else
                $Tokens = array_shift($Tokens);

            if ($Tokens === null)
                $Tokens = [];

            $Tokens = F::Dot($Tokens, $Token, $Call['Request']['Translation']);

            ksort($Tokens);

            if (null !== F::Run('IO', 'Write',
                        [
                              'Storage' => 'Locale',
                              'Scope'   => $Asset.'/Locale/'.$Call['Locale'],
                              'Where'   => $ID,
                              'Data' => $Tokens
                        ]))

                $Call['Output']['Content'] = true;
            else
                $Call['Output']['Content'] = false;
        }

        return $Call;
    });