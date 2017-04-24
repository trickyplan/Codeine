<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        $Drivers = F::Run('Developer.Feature.Scan', 'Do', $Call);
        $Template = $Call['Developer']['Feature']['Template'];
        
        foreach ($Drivers as $Driver)
        {
            $Call['Developer']['Feature']['Title']      = strtr($Driver, DS, '.');
            $Call['Developer']['Feature']['Filename']   = Root.DS.'Features'.DS.$Driver.'.json';
            $Call['Developer']['Feature']['Output']     = F::Live($Template, $Call['Developer']['Feature']);
            
            $Directory = dirname($Call['Developer']['Feature']['Filename']);
            
            if (is_dir($Directory))
                ;
            else
                mkdir($Directory, 0777, true);
            
            file_put_contents($Call['Developer']['Feature']['Filename'], j($Call['Developer']['Feature']['Output']));
        }
        
        return $Call;
    });