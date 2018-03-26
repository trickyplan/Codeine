<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Test.Case.Run.Execute.Before', function ($Call)
    {
        // Vitalize Call
        $Call = F::Dot($Call, 'Test.Case.Run.Call',
            F::Live(
                F::Dot($Call, 'Test.Case.Run.Call'), $Call['Virtual']
            ));
        return $Call;
    });