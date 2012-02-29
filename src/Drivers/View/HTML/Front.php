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

            $sz = sizeof($Slices);

            $Asset = $Slices[0];

            $IDs = array('Main');
            for ($ic = 1; $ic < $sz; $ic++)
                $IDs[] = implode('/', array_slice($Slices, 1, $ic));

            foreach ($IDs as $ID)
                if (null !== (($Sublayout = F::Run('View', 'LoadParsed', $Call,
                    array (
                          'Scope' => $Asset,
                          'ID'    => $ID,
                          'Data'  => isset($Call['Front']) ? $Call['Front'] : array ()
                    ))))
                )
                {
                    $Call['Layout'] = str_replace('<place>Content</place>', $Sublayout, $Call['Layout']);
                }
        }

        return $Call;
     });