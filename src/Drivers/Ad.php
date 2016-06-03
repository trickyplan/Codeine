<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        $Call = F::Apply(null, 'Input', $Call);
        $Call = F::Apply(null, 'Render', $Call);
        $Call = F::Apply(null, 'Output', $Call);

        return $Call;
    });

    setFn('Input', function ($Call)
    {

        return $Call;
    });

    setFn('Render', function ($Call)
    {

        return $Call;
    });

    setFn('Output', function ($Call)
    {

        return $Call;
    });