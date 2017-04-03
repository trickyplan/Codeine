<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Check', function ($Call)
    {
        if (isset($Call['HTTP']['Request']['Headers']['Pragma']) && $Call['HTTP']['Request']['Headers']['Pragma'] == 'no-cache')
        {
            F::Log('No cache header detected, force update initiated', LOG_INFO);
            $Call['Data'] = F::Run('Entity', 'Read', $Call, ['One' => true, 'Limit' => ['From' => 0, 'To' => 1]]);
            
            F::Run('Entity', 'Update', $Call, [
                'Entity' => $Call['Entity'],
                'Where'  => $Call['Where'],
                'Data'   => $Call['Data']
            ]);
        }

        return $Call;
    });