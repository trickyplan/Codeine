<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('beforeRequestRun', function ($Call)
    {
        $Call['Project'] = F::Live(F::loadOptions('Project'));
        $Call['Version']['Project'] = $Call['Version'][$Call['Project']['ID']];

        return $Call;
     });