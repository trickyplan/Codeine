<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        return F::Dot($Call, 'Test.Case.Result.Size', mb_strlen(F::Dot($Call, 'Test.Case.Result.Actual')));
    });