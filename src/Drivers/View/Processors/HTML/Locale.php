<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 6.0
     */

    self::Fn('Process', function ($Call)
    {
        if (preg_match_all('@<l>(.*)<\/l>@SsUu', $Call['Output'], $Pockets))
        {
            // FIXME Codeinize
            $Locales = F::Run(
                array(
                    '_N' => 'Engine.Object',
                    '_F' => 'Load',
                    'Scope' => 'Language',
                    'ID' => 'Russian'
                )
            );

            $Locales = $Locales['Russian']; // Temporary

            foreach ($Pockets[1] as $IX => $Match)
            {
                if (isset($Locales[$Match]))
                    $Call['Output'] = str_replace($Pockets[0][$IX], $Locales[$Match], $Call['Output']);
                else
                    $Call['Output'] = str_replace($Pockets[0][$IX], '<nl>'.$Match.'</nl>', $Call['Output']);
            }
        }
        return $Call;
    });
