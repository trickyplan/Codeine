<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn('Count', function ($Call)
    {
        return sizeof(F::Run('Entity', 'Read',
                    array(
                         'Entity' => $Call['Linked'],
                         'Where' =>
                             [
                                $Call['Entity'] => $Call['Data']['ID']
                             ]
                    )));
    });