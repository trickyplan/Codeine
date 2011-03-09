<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Fluid Schema (ex SQL3D)
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 15.11.10
     * @time 23:52
     */

    self::Fn('Create', function ($Call)
    {
        $Rows = array();

        foreach ($Call['Call']['Data'] as $Key => $Value)
        {
            if (!is_array($Value))
                $Value = array($Value);

            foreach ($Value as $cValue)
                $Rows[] = array('ID' => $Call['Call']['Data']['ID'], 'K' => $Key, 'V' => $cValue);
        }

        $Call['Call']['Data'] = $Rows;
        $Call['Call']['Multiple'] = true;
        return $Call['Call'];
    });

    self::Fn('Update', function ($Call)
    {
        var_dump($Call);
        die();

        foreach ($Call['Set'] as $Key => $Value)
        {
// TODO
        }

        return $Call['Call'];
    });

    self::Fn('afterRead', function ($Call)
    {
        $Data = array();
        
        foreach ($Call['Result'] as $Row)
            $Data[$Row['ID']][$Row['K']][] = $Row['V'];

        foreach ($Data as &$Rows)
            foreach ($Rows as $Key => &$Value)
                if (count($Value) == 1)
                    $Value = $Value[0];

        return $Data;
    });
