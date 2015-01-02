<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Check', function ($Call)
    {
        if (isset($Call['Session']['User']['Rights']))
            $UserRights = (array) explode(',', $Call['Session']['User']['Rights']);
        else
            $UserRights = [];

        if (!empty($UserRights))
        {
            foreach ($Call['Access']['Rights'] as $Name => $Rule)
                if (($Diff = F::Diff($Rule, $Call['Run'])) === null)
                {
                    $Call['Decision'] = in_array($Name, $UserRights);

                    F::Log('Right applied: '.$Name, LOG_INFO);

                    break;
                }
            F::Log('Final decision:'. ($Call['Decision']? 'Allow': 'Deny'), LOG_INFO);

        }


        return $Call;
    });