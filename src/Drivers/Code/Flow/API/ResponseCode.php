<?php

    setFn('afterAPIRun', function ($Call)
    {
        if (F::Dot($Call, 'Output.Content.Response.Data.HTTPCode'))
        {
            $Call['HTTP']['Headers']['HTTP/1.1'] = F::Dot($Call,'Output.Content.Response.Data.HTTPCode');
            unset($Call['Output']['Content']['Response']['Data']['HTTPCode']);
        }
    
        return $Call;
    });