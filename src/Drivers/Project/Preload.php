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
        $Call['Version'] = F::loadOptions('Version');
        $Call['Version']['Project'] = $Call['Version'][$Call['Project']['ID']];
        F::Log('Project Version: *'.$Call['Version']['Project']['Major'].'*', LOG_INFO);

        return $Call;
     });