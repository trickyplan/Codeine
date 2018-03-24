<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Test.Case.Run.Execute.After', function ($Call)
    {
        if (F::Dot($Call, 'Test.Case.Result.Store'))
            $Call = F::Dot($Call,
                'Virtual.'.F::Dot($Call, 'Test.Case.Result.Store')
                , F::Dot($Call, 'Test.Case.Result.Actual'));
            
        return $Call;
    });