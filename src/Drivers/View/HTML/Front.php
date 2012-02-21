<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Layouts', function ($Call)
    {
        if (F::isCall($Call))
        {
            $Slices = explode('.', $Call['Service']);
            $Slices[] = $Call['Method'];

            $sz = sizeof($Slices);

            for ($ic = 0; $ic < $sz; $ic++)
            {
                $ID = implode('/', array_slice($Slices, 0, $ic));

                if ($Sublayout = F::Run('View', 'LoadParsed',
                    array (
                          'Scope' => strtolower($Call['Service']),
                          'ID'=> $ID,
                          'Data' => isset($Call['Front'])? $Call['Front']:array()
                    )))
                    $Call['Layout'] = str_replace('<place>Content</place>', $Sublayout, $Call['Layout']);
            }
        }

        return $Call;
     });