<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Before', function ($Call)
    {
        $Call['Exist'] = F::Run('Entity', 'Read', $Call);
        return $Call;
    });

    setFn('Do', function ($Call)
    {
        if (null === $Call['Exist'])
            $Call = F::Run('Entity.Create', 'Do', $Call, ['Where' => null]);
        else
        {
            $Call = F::Run('Entity.Update', 'Before', $Call);
            $Call = F::Run('Entity.Update', 'Do', $Call);
        }

        return $Call;
    });