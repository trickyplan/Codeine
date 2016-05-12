<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        if (isset($Call['JS']['Obfuscate']['Modes']) && !empty($Call['JS']['Obfuscate']['Modes']))
            foreach ($Call['JS']['Obfuscate']['Mode'] as $Obfuscator)
                $Call = F::Apply(F::Run('View.JS.Obfuscator'.$Obfuscator, null, $Call));

        return $Call;
    });