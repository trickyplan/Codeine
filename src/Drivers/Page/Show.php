<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Where'] = F::Live($Call['Where']);

        $Call['Data'] = F::Run('Entity', 'Read', $Call)[0];

        if (isset($Call['Data']['Redirect']) && !empty($Call['Data']['Redirect']))
            $Call = F::Run('System.Interface.Web','Redirect', $Call, ['Location' => $Call['Data']['Redirect']]);
        else
            $Call = F::Run('Entity.Show.Static', 'Do', $Call);

        return $Call;
    });