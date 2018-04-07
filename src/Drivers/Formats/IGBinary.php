<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: 
     * @package Codeine
     * @version 8.x
     */

    setFn('Read', function ($Call)
    {
        if (F::Dot($Call, 'Base64'))
            $Call['Value'] = base64_decode($Call['Value']);
        
        $Call['Value'] =igbinary_unserialize($Call['Value']);
        
        return $Call['Value'];
    });

    setFn('Write', function ($Call)
    {
        $Call['Value'] = igbinary_serialize($Call['Value']);
        
        if (F::Dot($Call, 'Base64'))
            $Call['Value'] = base64_encode($Call['Value']);
        
        return $Call['Value'];
    });