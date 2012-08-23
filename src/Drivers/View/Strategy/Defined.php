<?php

    /* Codeine
     * @author BreathLess
     * @description: Select Renderer
     * @package Codeine
     * @version 7.x
     */

    self::setFn ('Select', function ($Call)
    {
        return array('Service' => (isset($Call['Renderer'])? $Call['Renderer']: $Call['Default']), 'Method' => 'Render');
    });
