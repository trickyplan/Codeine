<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 6.0
     * @issue 30
     */

    self::Fn('Render', function ($Call)
    {

        // TODO Стратегия
        $Renderer = F::Run($Call, array(
              '_N' => 'View.Strategy.Renderer',
              '_F' => 'Select'
                    ));

        $Call = F::Run($Call,
            array(
                '_N' => 'View.Render.'.$Renderer,
                '_F' => 'Process'
            )
        );

        foreach ($Call['Postprocessors'] as $Processor)
           $Call = F::Run($Call, $Processor);

        return $Call;
    });
