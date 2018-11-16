<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $Diff = F::Diff(F::Dot($Call, 'Test.Case.Result.Diff.Expected'), F::Dot($Call, 'Test.Case.Result.Actual'));
        if ($Diff === null)
            $Decision = true;
        else
        {
            $Call['Failure'] = true;
            $Decision = false;
            F::Log(
                j(F::Dot($Call, 'Test.Case.Result.Actual'))
                .' is too different from '
                .j(F::Dot($Call, 'Test.Case.Result.Diff.Expected'))
                , LOG_WARNING, 'Developer');
            F::Log($Diff, LOG_WARNING, 'Developer');
        }

        return $Decision;
    });