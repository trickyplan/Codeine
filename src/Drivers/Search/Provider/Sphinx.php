<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Sphinx Driver 
     * @package Codeine
     * @version 8.x
     */

    setFn('Query', function ($Call)
    {
        $Data = null;

        // Собственно поиск
        $Sphinx = new SphinxClient();

        if ($Sphinx->setServer($Call['Server'], $Call['Port']))
        {
            // ищем хотя бы 1 слово  из поисковой фразы
            // FIXME Добавить опций
            $Sphinx->setLimits($Call['Limits']['From'], $Call['Limits']['To'], $Call['Limits']['To']);
            if ($Sphinx->setMatchMode(SPH_MATCH_ANY))
            {
                // поисковый запрос
                if ($Result = $Sphinx->query($Call['Query'], strtolower($Call['Entity'])))
                {
                    if ($Result['total'] >0)
                    {
                        $Data = [];
                        foreach ($Result['matches'] as $ID => $Match)
                            $Data[$ID] = $Match['weight'];
                    }
                }
                else
                    $Call = F::Hook('Sphinx.FailedQuery', $Call);
            }
            else
                $Call = F::Hook('Sphinx.FailedMode', $Call);
        }
        else
            $Call = F::Hook('Sphinx.CantConnect', $Call);



        return $Data;
     });