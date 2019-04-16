<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: Simple HTML Renderer
     * @package Codeine
     * @version 8.x
     */

    setFn('Render', function ($Call)
    {
        $RAWKey = F::Dot($Call,'View.RAW.Key');
      
        if ($RAWData = F::Dot($Call['Output'], $RAWKey))
        {
            if (is_scalar($RAWData))
                $Call['Output'] = $RAWData;
            else
                $Call['Output'] = j($RAWData);
        }
        else
            $Call['Output'] = null;
        
        if (F::Dot($Call, 'HTTP.Headers.Content-Type:'))
            ;
        else
        {
            if (is_scalar($Call['Output']))
            {
                $finfo = new finfo(FILEINFO_MIME);
                $Call = F::Dot($Call, 'HTTP.Headers.Content-Type:', $finfo->buffer($Call['Output']));
            }
        }

        return $Call;
    });
