<?php

    /* Codeine
     * @author BreathLess
     * @description: Select Renderer
     * @package Codeine
     * @version 6.0
     */

    self::setFn ('Defined', function ($Call)
    {
        return isset($Call['Renderer'])? $Call['Renderer']: 'HTML';
    });

    self::setFn('Select', function ($Call)
    {
        foreach ($Call['Renderers'] as $Renderer)
            if (F::Run(array(
                  '_N' => 'View.Render.'.$Renderer,
                  '_F' => 'Detect',
                  'Value' => $Call['Value']
                        )))
                return $Renderer;
        
        return null;
    });
