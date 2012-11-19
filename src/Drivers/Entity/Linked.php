<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Count', function ($Call)
    {
        if (isset($Call['Data'][0]['ID']))
            return F::Run('Entity', 'Count',
                    array(
                         'Entity' => $Call['Linked'],
                         'Where' =>
                             [
                                $Call['Entity'] => $Call['Data'][0]['ID']
                             ]
                    ));
        else
            return null;
    });