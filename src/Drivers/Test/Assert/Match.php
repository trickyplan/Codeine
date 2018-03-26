<?php

setFn('Do', function ($Call) 
{
    
    if (preg_match(F::Dot($Call, 'Test.Case.Result.Match.Expected'), F::Dot($Call, 'Test.Case.Result.Actual')))
        $Decision = true;
    else
    {
        $Call['Failure'] = true;
        $Decision = false;
        F::Log(
            j(F::Dot($Call, 'Test.Case.Result.Actual'))
            .' is not match '
            .j(F::Dot($Call, 'Test.Case.Result.Match'))
            , LOG_WARNING, 'Developer');
    }

    return $Decision;
});