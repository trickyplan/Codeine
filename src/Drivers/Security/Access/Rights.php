<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Check', function ($Call)
    {
        if (isset($Call['Session']['User']))
        {
            $UserRights = (array) explode(',', $Call['Session']['User']['Rights']);

        foreach ($Call['Access']['Rights'] as $Name => $Rule)
            if (($Diff = F::Diff($Rule, $Call['Run'])) === null)
            {
                F::Log('Права: Применено правило: '.$Name, LOG_INFO);
                $Call['Decision'] = in_array($Name, $UserRights);
            }
/*            else
                d(__FILE__, __LINE__, $Diff);*/
        }

        F::Log('Права: Итоговое решение:'. ($Call['Decision']? 'Разрешить': 'Запретить'), LOG_INFO);

        return $Call;
    });