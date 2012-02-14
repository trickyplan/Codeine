<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 7.1
     * @issue 30
     */

    self::setFn('Render', function ($Call)
    {
        $Renderer = F::Run($Call['Strategy']['Renderer']['Service'], $Call['Strategy']['Renderer']['Method'], $Call);

        $Call = F::Run($Renderer['Service'], $Renderer['Method'], $Call);

        if (isset($Call['Postprocessors']))
            foreach ($Call['Postprocessors'] as $Processor)
               $Call = F::Run($Processor['Service'], $Processor['Method'], $Call, isset($Processor['Call'])? $Processor['Call']: null);

        return $Call;
    });