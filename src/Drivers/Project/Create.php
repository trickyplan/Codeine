<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        foreach($Call['Project']['Create']['Handlers'] as $Driver)
            F::Run('Project.Create.'.$Driver, 'Do', $Call);

        return $Call;
    });