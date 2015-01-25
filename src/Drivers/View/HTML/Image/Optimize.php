<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $Cache = F::Run('IO', 'Open', ['Storage' => 'Image Cache']);
        $Call['Image']['Cached Filename'] = $Cache['Directory'].DS.$Call['HTTP']['Host'].DS.'img'.DS.$Call['Image']['Scope'].DS.$Call['Image']['Fullpath'];
        $Type = mime_content_type($Call['Image']['Cached Filename']);

        if (isset($Call['Image Optimizers'][$Type]))
        {
            foreach ($Call['Image Optimizers'][$Type] as $Optimizer)
                $Call = F::Run('View.HTML.Image.Optimize.'.$Optimizer, null, $Call);
        }

        return $Call;
    });