<?php

    /* Codeine
     * @author BreathLess
     * @description: Select Renderer
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn ('Select', function ($Call)
    {
        return array('Service' => (isset($Call['Renderer'])? $Call['Renderer']: $Call['Default']), 'Method' => 'Render');
    });
