<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        if (isset($Call['Data'][$Call['Key']]))
        {
            $Call['Value'] = $Call['Data'][$Call['Key']];

            foreach ($Call['Beautifiers'] as $Rule)
                $Call = F::Apply('Text.Beautifier.'.$Rule, 'Process', $Call);

            return html_entity_decode($Call['Value']);
        }

        return null;
    });