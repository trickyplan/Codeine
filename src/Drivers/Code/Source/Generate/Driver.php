<?php

    /* Codeine
     * @author BreathLess
     * @description: Driver Generator
     * @package Codeine
     * @version 6.0
     */

    self::Fn('Do', function ($Call)
    {
        // FIXME Data Read
        $Source = file_get_contents(Codeine.'Drivers/Stub.php');

        foreach ($Call['Functions'] as $Name => &$Function)
        {
    $Function = "\n".'self::Fn(\''.$Name.'\', function ($Call)
    {
        '.$Function.'
    });'."\n";
        }

        $Call['Body'] = implode('', $Call['Functions']);

        if (preg_match_all('@\$\{(.*)\}@SsUu', $Source, $Pockets))
            foreach ($Pockets[0] as $IX => $Match)
                if (isset($Call[$Pockets[1][$IX]]))
                    $Source = str_replace('// $'.$Match, $Call[$Pockets[1][$IX]], $Source);

        return $Source;
    });
