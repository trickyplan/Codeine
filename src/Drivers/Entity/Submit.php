<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Hook('beforeEntitySubmitDo', $Call);
            $Call = F::Apply('Entity.Create', 'Do', $Call);
        $Call = F::Hook('afterEntitySubmitDo', $Call);

        return $Call;
    });