<?php

    /* Codeine
     * @author BreathLess
     * @description: Simple HTML Renderer
     * @package Codeine
     * @version 6.0
     */

    self::setFn('Detect', function ($Call)
    {
        return is_array($Call['Value']);
    });

    self::setFn('Process', function ($Call)
    {

        $Call = F::Run($Call, array('_N' => 'Code.Flow.Hook','_F'=>'Run','On'=>'beforeRender'));   // JP beforeRender

        $Call = F::Run($Call, array('_N' => 'Code.Flow.Hook','_F'=>'Run','On'=>'beforePipeline')); // JP beforePipeline

        $Call = F::Run($Call, array('_N' => 'View.Render.Pipeline','_F' => 'Process'));           // Pipelining

        $Call = F::Run($Call, array('_N' => 'Code.Flow.Hook','_F'=>'Run','On'=>'afterPipeline')); // JP afterPipeline

        $Call = F::Run($Call, array('_N' => 'Code.Flow.Hook','_F'=>'Run','On'=>'afterRender'));   // JP afterRender

        return $Call;
    });
