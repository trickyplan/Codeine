<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Native Router (ex-prepare)
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 25.02.11
     * @time 15:53
     */

    self::Fn('Route', function ($Call)
    {
        $Call = $Call['Call'];

        if (is_array($Call))
        {
            if (isset($Call['NFD']))
            {
                $NFs = preg_split('@\.@', $Call['NFD']);
                $Call['F'] = $NFs[sizeof($NFs)-2];
                $Call['D'] = $NFs[sizeof($NFs)-1];
                $Call['N'] = implode('/',array_slice($NFs, 0, sizeof($NFs)-2));
            }
            elseif (isset($Call['NF']))
            {
                $NFs = preg_split('@\.@', $Call['NF']);
                $Call['F'] = $NFs[sizeof($NFs)-1];
                $Call['N'] = implode('/',array_slice($NFs, 0, sizeof($NFs)-1));
            }
            else
                return null;

            return $Call;
        }
        else
            return null;
    });
