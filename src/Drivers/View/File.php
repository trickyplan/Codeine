<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: Simple HTML Renderer
     * @package Codeine
     * @version 8.x
     */

    setFn('Render', function ($Call)
    {
        if ($Call['Output']['Content'] === null)
            ;
        else
        {
            $Call['HTTP']['Headers']['Content-type:'] = mime_content_type($Call['Output']['Content']);
            $Call['Output'] = file_get_contents($Call['Output']['Content']);
        }
        
        return $Call;
    });
