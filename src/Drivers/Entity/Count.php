<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Apply('Entity', 'Load', $Call);

        if (isset($Call['Selector']))
            $Call['Where'] = F::Merge($Call['Where'], F::Live($Call['Selector']));

        $Call = F::Hook('beforeCount', $Call);

            $Call['Count'] = F::Run('Entity', 'Count', $Call);

        $Call = F::Hook('afterCount', $Call);

        return $Call['Count'];
    });