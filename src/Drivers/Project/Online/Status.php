<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Output']['Content'] = F::findFile(F::Dot($Call, 'Project.Online.Status.URL'));
        return $Call;
    });