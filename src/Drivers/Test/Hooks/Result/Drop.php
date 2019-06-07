<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        if (F::Dot($Call, 'Test.Case.Result.Drop'))
            $Call = F::Dot($Call, 'Test.Case.Result.Actual', '[Dropped]');

        return $Call;
    });