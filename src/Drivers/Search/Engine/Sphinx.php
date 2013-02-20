<?php

    /* Codeine
     * @author BreathLess
     * @description Sphinx Driver 
     * @package Codeine
     * @version 7.x
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
            if ($Sphinx->setMatchMode(SPH_MATCH_ANY))
            {
                // поисковый запрос
                if ($Result = $Sphinx->query($Call['Query'], strtolower($Call['Entity'])))
                {
                    if ($Result['total'] >0)
                    {
                        $Data = array();
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