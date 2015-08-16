<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Convert', function ($Call)
    {
        $Value = morpher_inflect($Call['Value'], $Call['Morpher']['Cases mapping'][$Call['Case']]);

        if (empty($Value) or $Value{0} == '#')
        {
            switch ($Call['Case'])
            {
                case 'Praepositionalis':
                   $Call['Value'] = 'о '.$Call['Value'];
                break;
            }

            return $Call['Value'];
        }
        else
            return $Value;
    });