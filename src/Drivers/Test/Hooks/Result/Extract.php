<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        // Fetch Key
        if ($Extract = F::Dot($Call, 'Test.Case.Result.Extract'))
            $Call = F::Dot($Call, 'Test.Case.Result.Actual',
                F::Dot($Call, 'Test.Case.Result.Actual.'.$Extract));
            
        return $Call;
    });