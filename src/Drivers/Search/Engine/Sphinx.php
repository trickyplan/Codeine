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

        $Sphinx = new SphinxClient();
        $Sphinx->setServer( '127.0.0.1', 9312 );

        // Собственно поиск
        $Sphinx->setMatchMode( SPH_MATCH_ANY  ); // ищем хотя бы 1 слово из поисковой фразы

        $Result = $Sphinx->query($Call['Query']); // поисковый запрос

        if ($Result['total'] >0)
        {
            $Data = array();
            foreach ($Result['matches'] as $ID => $Match)
                $Data[$ID] = $Match['weight'];
        }

        return $Data;
     });