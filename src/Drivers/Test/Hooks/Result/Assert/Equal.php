<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call) {
        if (F::Dot($Call, 'Test.Case.Result.Actual') == F::Dot($Call, 'Test.Case.Assert.Equal.Expected')) {
            $Decision = true;
        } else {
            $Call['Failure'] = true;
            $Decision = false;
            F::Log('Result is not equal to Assert', LOG_WARNING, 'Developer');
            F::Log(F::Dot($Call, 'Test.Case.Result.Actual'), LOG_WARNING);
            F::Log(F::Dot($Call, 'Test.Case.Result.Equal.Expected'), LOG_WARNING);
        }

        return $Decision;
    });
