<?php

    /* Codeine
     * @author BreathLess
     * @description: Select Renderer
     * @package Codeine
     * @version 6.0
     */

    self::Fn('Select', function ($Call)
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
