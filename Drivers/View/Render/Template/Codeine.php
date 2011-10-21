<?php

    /* Codeine
     * @author BreathLess
     * @description: D8 Port
     * @package Codeine
     * @version 6.0
     */

    self::Fn('Detect', function ($Call)
    {
        foreach ($Call['Value'] as $Place => &$Items)
            foreach ($Items as $Item)
                if (!isset($Item['UI']))
                    return false;
        
        return true;
    });

    self::Fn('Process', function ($Call)
    {
        $Layout = F::Run(array(
                             '_N' => 'Engine.Data',
                             '_F' => 'Read',
                             'Point' => 'Layout',
                             'ID' => 'Test/Digest.html'
                         ));

        foreach ($Call['Value'] as $Place => &$Items)
        {
            $Output = '';
            foreach ($Items as $Item)
                $Output.= F::Run(
                    F::Merge($Item,
                        array(
                            '_N' => 'View.UI.Template.Codeine.'.$Item['UI'],
                            '_F' => 'Make'
                        )));
            $Layout = str_replace('<place>'.$Place.'</place>', $Output, $Layout);
        }

        return $Layout;
    });
