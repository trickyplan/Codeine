<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        if (isset($Call['CSS']['Compress']['Modes']) && !empty($Call['CSS']['Compress']['Modes']))
        {
            F::Log(function () use ($Call) {return 'CSS Compressors loaded: '.implode(',', $Call['CSS']['Compress']['Modes']);} , LOG_DEBUG);
            foreach ($Call['CSS']['Compress']['Modes'] as $Compressor)
                $Call = F::Apply('View.CSS.Compress.'.$Compressor, null, $Call);
        }

        return $Call;
    });