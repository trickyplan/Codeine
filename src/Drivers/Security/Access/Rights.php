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
        {
            $UserRights = (array) explode(',', $Call['Session']['User']['Rights']);

            foreach ($Call['Access']['Rights'] as $Name => $Rule)
                if (($Diff = F::Diff($Rule, $Call['Run'])) === null)
                {
                    F::Log('Права: Применено правило: '.$Name, LOG_INFO);
                    $Call['Decision'] = in_array($Name, $UserRights);
                }
        }

        F::Log('Rights decision:'. ($Call['Decision']? 'Allow': 'Deny'), LOG_INFO);

        unset($Call['Access']);
        return $Call;
    });