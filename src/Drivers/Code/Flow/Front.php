<?php

    /* Codeine
     * @author BreathLess
     * @description: Фронт контроллер
     * @package Codeine
     * @version 6.0
     * @date 31.08.11
     * @time 1:12
     */

    self::Fn('Run', function ($Call)
    {
        F::Run(
                array(
                    '_N' => 'System.Output.HTTP',
                    '_F' => 'Initialize'
                )
              );

        $Output = array();

        foreach ($Call['Interfaces'] as $Interface)
            if (F::Run(array('_N'=>'System.Input.'.$Interface,'_F' => 'Detect')))
                $Call['Value']  = F::Run(array('_N' => 'System.Input.'.$Interface, '_F' => 'Get'));

        $Call = F::Run($Call, array('_N' => 'Code.Flow.Hook','_F'=>'Run','On'=>'beforeRun')); // JP beforeRun


        if (F::isCall($Call['Value']))
            $Call  = F::Run ($Call, $Call['Value']);
        else
            $Call  = F::Run($Call, array('_N' => 'Code.Flow.Hook','_F'=>'Run','On'=>'Routing.Failed'));

        // Передаём его в рендерер

        $Call  = F::Run($Call,
                    array(
                        '_N' => 'Engine.View',
                        '_F' => 'Render'
                    )
                );

        F::Run($Call,
                array(
                    '_N' => 'System.Output.HTTP',
                    '_F' => 'Do'
                )
            );

        return true;
    });
