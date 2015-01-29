<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Run', function ($Call)
    {
        $Script = F::loadOptions($Call['Run'], null, [], 'Scripts');

        $VCall = $Call;
        $VCall['Environment'] = F::Environment();

        foreach ($Script as $Run)
            $VCall = F::Live($Run, $VCall);

        $Call['Output']['Content'] = [$VCall];

        return $Call;
    });