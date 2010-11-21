<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Driver Generator
     * @package Codeine
     * @subpackage Drivers
     * @version 0.1
     * @date 21.11.10
     * @time 3:40
     */

    $Generate = function ($Call)
    {
        $Source = file_get_contents(Data::Locate('Code','Driver.stub'));

        foreach ($Call['Functions'] as $Name => &$Function)
        {
    $Function = '
    $'.$Name.' = function ($Call)
    {
        '.$Function.'
    };
';
        }

        $Call['Body'] = implode("", $Call['Functions']);
        
        if (preg_match_all('@\$\{(.*)\}@SsUu', $Source, $Pockets))
            foreach ($Pockets[0] as $IX => $Match)
                if (isset($Call[$Pockets[1][$IX]]))
                    $Source = str_replace($Match, $Call[$Pockets[1][$IX]], $Source);

        return $Source;
    };