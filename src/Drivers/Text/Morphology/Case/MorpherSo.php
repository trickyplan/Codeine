<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Convert', function ($Call)
    {
        if (preg_match_all('/\"(.*)\"/Ssu', $Call['Value'], $Pockets))
        {
            foreach ($Pockets[0] as $IX => $Match)
                $Call['Value'] = str_replace($Match, '$'.$IX.'$', $Call['Value']);

        }

        $Value = morpher_inflect($Call['Value'], $Call['Morpher']['Cases mapping'][$Call['Case']]);

        if (empty($Value) or $Value{0} == '#')
        {
            switch ($Call['Case'])
            {
                case 'Praepositionalis':
                   $Call['Value'] = 'о '.$Call['Value'];
                break;
            }

            if (preg_match_all('/\"(.*)\"/Ssu', $Call['Value'], $Pockets))
            {
                foreach ($Pockets[0] as $IX => $Match)
                    $Call['Value'] = str_replace($Match, '$'.$IX.'$', $Call['Value']);

            }
        }
        else
            $Call['Value'] = $Value;

        if (preg_match_all('/\$(.*)\$/Ssu', $Call['Value'], $Restore))
        {
            foreach ($Restore[0] as $IX => $Match)
                $Call['Value'] = str_replace($Match, $Pockets[0][$IX], $Call['Value']);

        }

        return $Call['Value'];
    });