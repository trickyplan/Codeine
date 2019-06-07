<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        if (F::Dot($Call, 'Test.Case.Result.Base64'))
            $Call = F::Dot($Call, 'Test.Case.Result.Actual',
                base64_encode(F::Dot($Call, 'Test.Case.Result.Actual')));

        return $Call;
    });