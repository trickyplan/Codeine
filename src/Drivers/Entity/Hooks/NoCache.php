<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Check', function ($Call)
    {
        if (F::Dot($Call, 'No-Cache Check'))
            if (F::Dot($Call, 'HTTP.Request.Headers.Pragma') == 'no-cache')
            {
                F::Log('No-cache header detected, force update initiated', LOG_INFO);
    
                $Data  = F::Run('Entity', 'Read', $Call, ['One' => true]);
                
                if (isset($Data['ID']))
                    F::Run('Entity', 'Update', $Call,
                        [
                            'Where!' => $Data['ID'],
                            'Data'  => $Data
                        ]);
            }

        return $Call;
    });