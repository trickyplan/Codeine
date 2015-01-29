<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        if (isset($Call['Service']) && $Call['View']['Renderer'] == 'View.HTML')
        {
            if (in_array($Call['Service'], $Call['No SEO Audit']))
                F::Log('SEO Audit skipped', LOG_INFO, 'Marketing');
            else
                if (isset($Call['View']['HTML']) && !isset($Call['Headers']['Location:']))
                    foreach($Call['Auditors'] as $Auditor)
                        $Call = F::Live($Auditor, $Call);
        }

        return $Call;
    });