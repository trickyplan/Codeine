<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Write', function ($Call)
    {
        $Call = F::Run('Entity', 'Load', $Call, ['Entity' => $Call['Name']]);

        $Call['Value'] = F::Run('Data.Type.'.$Call['Nodes']['ID']['Type'], 'Write', ['Value' => $Call['Value']]);

        $Element = F::Run('Entity', 'Read', ['One' => true, 'Entity' => $Call['Name'], 'Where' => $Call['Value']]);

        if (empty($Element))
            $Call['Value'] = null;
        else
            F::Run('Entity', 'Touch', ['One' => true, 'Entity' => $Call['Name'], 'Where' => $Call['Value']]);

        return $Call['Value'];
    });

    setFn(['Read', 'Where'], function ($Call)
    {
        return $Call['Value'];
    });

    setFn('Populate', function ($Call)
    {
        return null;
    });