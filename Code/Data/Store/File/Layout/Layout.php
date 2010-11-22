<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: LayoutFS Driver
     * @package Codeine
     * @subpackage Drivers
     * @version 0.2
     * @date 18.11.10
     * @time 5:37
     */

    self::Fn('Connect', function ($Call)
    {
        if (isset($Call['Point']['DSN']))
            return $Call['Point']['DSN'];
        else
            return 'Default';
    });

    self::Fn('Read', function ($Call)
    {
        if (!is_array ($Call['Data']['ID']))
            $IDs = array ($Call['Data']['ID']);
        else
            $IDs = $Call['Data']['ID'];

        $Layout = '';
        $Candidates = array();

        $SZC = count($IDs);

        $IC = 0;

        for ($a = 0; $a<$SZC; $a++)
            {
                $Candidates[$IC++] = Root.$Call['Point']['Scope'].DS.$IDs[$a].'.html';
                $Candidates[$IC++] = Engine.'Default/'.$Call['Point']['Scope'].DS.$IDs[$a].'.html';
            }

        for ($a = 0; $a<$IC; $a++)
        {
            if (file_exists($Candidates[$a]))
                return file_get_contents($Candidates[$a]);
        }

        return '<content/>';
    });