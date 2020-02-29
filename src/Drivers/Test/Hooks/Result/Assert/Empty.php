<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        if (F::Dot($Call, 'Test.Case.Result.Empty.Expected') === empty(F::Dot($Call, 'Test.Case.Result.Actual')))
            $Decision = true;
        else
        {
            $Call['Failure'] = true;
            $Decision = false;
            
            if (F::Dot($Call, 'Test.Case.Result.Empty.Expected'))
                F::Log('Result is not empty, when expected to be', LOG_WARNING, 'Developer');
            else
                F::Log('Result is empty, when expected to not be', LOG_WARNING, 'Developer');
        }

        return $Decision;
    });