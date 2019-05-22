<?php

    setFn('Do', function ($Call) 
    {
        if (F::Dot($Call, 'HTTP.Method') == 'OPTIONS')
            $Call = F::Dot($Call, 'HTTP.Headers.Content-Type:', null);

        return $Call;
    });