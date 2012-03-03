<?php

    /* Codeine
     * @author BreathLess
     * @description: Simple HTML Renderer
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Render', function ($Call)
    {

       $Call = F::Run ('Code.Flow.Hook', 'Run', $Call, array('On' => 'beforeRender'));      // JP beforeRender

           $Call = F::Run ('Code.Flow.Hook', 'Run', $Call, array('On' => 'beforePipeline'));    // JP beforePipeline

               $Call = F::Run ('View.Pipeline','Process', $Call, array('Renderer' => 'View.HTML')); // Pipelining

           $Call = F::Run ('Code.Flow.Hook', 'Run', $Call, array ('On' => 'afterPipeline'));    // JP afterPipeline

       $Call = F::Run ('Code.Flow.Hook', 'Run', $Call, array ('On' => 'afterRender'));      // JP afterRender

       return $Call;
    });
