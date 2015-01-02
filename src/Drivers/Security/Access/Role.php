<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Check', function ($Call)
    {
        // Определить право

        foreach ($Call['Rights'] as $RID => $Right)
        {
            if (F::Diff($Right, $Call) === null) // Если нет различий
            {
                $Call['Right'] = $RID;
                break;
            }
        }

        // Определить роль

        if (isset($Call['Session']['User']['Role']))
            $Call['Role'] = F::Merge($Call['Role'], $Call['Session']['User']['Role']);

        // Проверить, если доступно

        if (isset($Call['Right']))
        {
            foreach ($Call['Role'] as $Role)
                if (isset($Call['Roles'][$Role])) // WOW!
                {
                    if (isset($Call['Roles'][$Role]['Rights'][$Call['Right']]))
                        $Call['Decision'] = $Call['Roles'][$Role]['Rights'][$Call['Right']];
                    else
                        F::Log('Permission for '.$Call['Right'].' not configured', LOG_WARNING);
                }
                else
                    F::Log('Unknown role', LOG_WARNING);
        }
        else
            F::Log('Unknown right', LOG_WARNING);

        return $Call;
    });