<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 7.2
     */

    setFn('beforeOperation', function ($Call)
    {
        // Quarantine
        if (false and isset($Call['Request']['Filter']) && is_array($Call['Request']['Filter']))
        {
            if (isset($Call['Where']))
                ;
            else
                $Call['Where'] = [];

            if (is_array($Call['Where']))
                foreach ($Call['Request']['Filter'] as $Key => $Value)
                    if (empty($Value))
                        ;
                    else
                    {
                        if (F::Dot($Call, 'Nodes.'.$Key.'.Filterable'))
                            $Call['Where'][$Key] = $Value;
                    }
        }
        return $Call;
    });