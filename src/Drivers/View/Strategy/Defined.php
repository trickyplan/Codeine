<?php

    /* Codeine
     * @author BreathLess
     * @description: Select Renderer
     * @package Codeine
     * @version 7.2
     */

    self::setFn ('Select', function ($Call)
    {
        return array('Service' => isset($Call['Renderer'])? $Call['Renderer']: $Call['Default'], 'Method' => 'Render');
    });
