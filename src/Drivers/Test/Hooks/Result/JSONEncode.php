<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        if (is_scalar(F::Dot($Call, 'Test.Case.Result.Actual')))
            ;
        else
        {
            if (F::Dot($Call, 'View.Render.Service') == 'View.JSON')
                ;
            else
                $Call = F::Dot($Call, 'Test.Case.Result.Actual', j(F::Dot($Call, 'Test.Case.Result.Actual'), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        }

        return $Call;
    });