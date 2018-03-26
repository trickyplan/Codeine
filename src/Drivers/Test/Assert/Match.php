<?php

setFn('Do', function ($Call) 
{
    
    if (preg_match($Call['Case']['Assert']['Match'], $Call['Case']['Result']['Actual']))
        $Decision = true;
    else
    {
        $Call['Failure'] = true;
        $Decision = false;
        F::Log(j($Call['Case']['Result']['Actual']).' is not match '.j($Call['Case']['Assert']['Match']), LOG_WARNING, 'Developer');
    }

    return $Decision;
});