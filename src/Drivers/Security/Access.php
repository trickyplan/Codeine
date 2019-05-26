<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Access Interface 
     * @package Codeine
     * @version 8.x
     */

    setFn('Check', function ($Call)
    {
        if ($Systems = (array) F::Dot($Call, 'Security.Access.System'))
            foreach ($Systems as $System)
                $Call = F::Apply('Security.Access.'.$System, 'Check', $Call);

        return $Call['Decision'];
    });

