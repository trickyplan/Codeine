<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        if (isset($Call['JS']['Compress']['Modes']) && !empty($Call['JS']['Compress']['Modes']))
        {
            F::Log(function () use ($Call) {return 'JS Compressors loaded: '.implode(',', $Call['JS']['Compress']['Modes']);} , LOG_DEBUG);
            foreach ($Call['JS']['Compress']['Modes'] as $Compressor)
                $Call = F::Apply('View.JS.Compress.'.$Compressor, null, $Call);
        }

        return $Call['JS']['Source'];
    });