<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: Simple HTML Renderer
     * @package Codeine
     * @version 8.x
     */

    setFn('Render', function ($Call)
    {
        if (is_array($Call['Output']['Content']))
            $Call['Output']['Content'] = implode('', $Call['Output']['Content']);
        
        $Call['Output'] = $Call['Output']['Content'];
        
        if (isset($Call['HTTP']['Headers']['Content-type:']))
            ;
        else
        {
            $finfo = new finfo(FILEINFO_MIME);
            $Call['HTTP']['Headers']['Content-type:'] = $finfo->buffer($Call['Output']);
        }

        return $Call;
    });
