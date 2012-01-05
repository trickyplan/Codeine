<?php

    /* Codeine
     * @author BreathLess
     * @description: Select Renderer
     * @package Codeine
     * @version 6.0
     */

    self::setFn ('Defined', function ($Call)
    {
        return array('Service' => isset($Call['Renderer'])? $Call['Renderer']: $Call['Default'], 'Method' => 'Render');
    });
