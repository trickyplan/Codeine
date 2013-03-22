<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Check', function ($Call)
    {
        if (isset($Call['Session']['User']['Rights']))
            $UserRights = (array) explode(',', $Call['Session']['User']['Rights']);
        else
            $UserRights = [];

        foreach ($Call['Access']['Rights'] as $Name => $Rule)
            if (($Diff = F::Diff($Rule, $Call['Run'])) === null)
            {
                F::Log('Right applied: '.$Name, LOG_INFO);
                $Call['Decision'] = in_array($Name, $UserRights);
            }

        F::Log('Decision:'. ($Call['Decision']? 'Allow': 'Deny'), LOG_INFO);

        return $Call;
    });