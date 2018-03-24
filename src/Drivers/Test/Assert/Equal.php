<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        if (F::Dot($Call, 'Test.Case.Result.Actual') == F::Dot($Call, 'Test.Case.Result.Equal.Expected'))
            $Decision = true;
        else
        {
            $Call['Failure'] = true;
            $Decision = false;
            F::Log(
                j(F::Dot($Call, 'Test.Case.Result.Actual'))
                .' is not equal to '
                .j(F::Dot($Call, 'Test.Case.Result.Equal'))
                , LOG_WARNING, 'Developer');
        }

        return $Decision;
    });