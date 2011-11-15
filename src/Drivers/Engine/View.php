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
              '_F' => 'Defined'
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

    self::Fn ('Load', function ($Call)
        {
            return F::Run(array(
                              '_N' => 'Engine.Data',
                              '_F' => 'Load',
                              'Storage' => 'Layout',
                              'Scope' => 'Layout',
                              'ID' => array($Call['ID'].$Call['Context'], $Call['ID'].'.html')
                          ));
    });