<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn('Count', function ($Call)
    {
        if (isset($Call['Data']['ID']))
            return sizeof(F::Run('Entity', 'Read', $Call,
                    array(
                         'Entity' => $Call['Linked'],
                         'Where' =>
                             [
                                $Call['Entity'] => $Call['Data']['ID']
                             ]
                    )));
        else
            return null;
    });