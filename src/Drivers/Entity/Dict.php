<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Get', function ($Call)
    {
        $Elements = F::Run('Entity', 'Read', array ('Entity' => $Call['Entity']));

        foreach($Elements as $Element)
            $Data[$Element['ID']] = $Element[$Call['Key']];

        return $Data;
    });