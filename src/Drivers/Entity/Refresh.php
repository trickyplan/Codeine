<?php

/* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.6.2
     */

    self::setFn('Do', function ($Call)
    {
        $Elements = F::Run('Entity', 'Read', $Call);

        foreach ($Elements as $Element)
            F::Run('Entity', 'Update', array('Entity' => $Call['Entity'], 'Where' => $Element['ID'], 'Data' => array()));

        return $Call;
     });