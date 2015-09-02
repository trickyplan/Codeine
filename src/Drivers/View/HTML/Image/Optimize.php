<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
       /* if (file_exists($Call['Image']['Cached Filename']))
        {
            $Type = mime_content_type($Call['Image']['Cached Filename']);

            if (isset($Call['Image Optimizers'][$Type]))
            {
                F::Log('Optimizers loaded: '.implode(',', $Call['Image Optimizers'][$Type]), LOG_INFO);

                foreach ($Call['Image Optimizers'][$Type] as $Optimizer)
                    $Call = F::Run('View.HTML.Image.Optimize.'.$Optimizer, null, $Call);
            }
            else
                F::Log('Unknown image type. No optimizers loaded.', LOG_INFO);
        }*/

        return $Call;
    });