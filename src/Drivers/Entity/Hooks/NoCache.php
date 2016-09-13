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
            F::Run('Entity', 'Update', $Call, [
                'Entity' => $Call['Entity'],
                'Where'  => $Call['Where']
            ]);

        return $Call;
    });