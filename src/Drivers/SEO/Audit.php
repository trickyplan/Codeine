<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        if (in_array($Call['Run']['Service'], $Call['No SEO Audit']))
            F::Log('SEO Audit skipped', LOG_INFO, 'Marketing');
        else
            if (isset($Call['View']['HTML']) && !isset($Call['Headers']['Location:']))
                foreach($Call['Auditors'] as $Auditor)
                    $Call = F::Live($Auditor, $Call);

        return $Call;
    });