<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Driver Generator
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 21.11.10
     * @time 3:40
     */


    self::Fn('Generate', function ($Call)
    {
        $Source = file_get_contents(Data::Locate('Code','Driver.stub'));

        foreach ($Call['Functions'] as $Name => &$Function)
        {
    $Function = '
    self::Fn(\''.$Name.'\', function ($Call)
    {
        '.$Function.'
    });
';
        }

        $Call['Body'] = implode('', $Call['Functions']);

        if (preg_match_all('@\$\{(.*)\}@SsUu', $Source, $Pockets))
            foreach ($Pockets[0] as $IX => $Match)
                if (isset($Call[$Pockets[1][$IX]]))
                    $Source = str_replace($Match, $Call[$Pockets[1][$IX]], $Source);

        return $Source;
    });