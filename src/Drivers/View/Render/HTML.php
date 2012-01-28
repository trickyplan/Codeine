<?php

    /* Codeine
     * @author BreathLess
     * @description: Simple HTML Renderer
     * @package Codeine
     * @version 7.0
     */

    self::setFn('Detect', function ($Call)
    {
        return is_array($Call['Value']);
    });

    self::setFn('Render', function ($Call)
    {
       // $Call['Layout'] = '<place>Content</place>';

      // $Call = F::Run ('Code.Flow.Hook', 'Run', $Call, array('On' => 'beforeRender')); // JP beforeRender

       $Call = F::Run ('Code.Flow.Hook', 'Run', $Call, array('On' => 'beforePipeline')); // JP beforePipeline

       $Call = F::Run('View.Render.Pipeline','Process', $Call, array('Renderer' => 'View.Render.HTML'));           // Pipelining

     /*   $Call = F::Run($Call, array('_N' => 'Code.Flow.Hook','_F'=>'Run','On'=>'afterPipeline')); // JP afterPipeline

        $Call = F::Run($Call, array('_N' => 'Code.Flow.Hook','_F'=>'Run','On'=>'afterRender'));   // JP afterRender*/

        return $Call;
    });
