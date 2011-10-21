<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 6.0
     */

    self::Fn('Make', function ($Call)
    {
        $Layout = F::Run(array(
                             '_N' => 'Engine.Data',
                             '_F' => 'Read',
                             'Point' => 'Layout',
                             'ID' => 'UI/Template/Codeine/Text/Heading.html'
                         ));

        if (preg_match_all('@<place>(.*)</place>@SsUu', $Layout, $Pockets))
        {
            foreach ($Pockets[0] as $Index => $Match)
            {
                if (isset($Call[$Pockets[1][$Index]]))
                    $Replace = $Call[$Pockets[1][$Index]];
                else
                    $Replace = ''; // TODO Event

                $Layout = str_replace($Match, $Replace, $Layout);
            }
        }

        return $Layout;
    });
