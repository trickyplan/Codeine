<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        if (F::Dot($Call, 'Test.Case.Result.Store'))
            $Call = F::Dot($Call,
                F::Dot($Call, 'Test.Case.Result.Store')
                , F::Dot($Call, 'Test.Case.Result.Actual'));
            
        return $Call;
    });