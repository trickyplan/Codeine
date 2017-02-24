<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('beforeHostDetect', function ($Call)
    {
        if (isset($Call['Project']['Regex Hosts']))
        {
            F::Log('Regex Hosts activated', LOG_INFO);
           
            foreach ($Call['Project']['Regex Hosts'] as $Name => $Rule)
            {
                if (preg_match_all('@'.$Rule['Regex'].'@Ssu', $_SERVER['HTTP_HOST'], $Pockets))
                {
                    F::Log('Regex Hosts Rule *'.$Name.'* matched ', LOG_INFO);
                    $Call['HTTP']['Override']['Matches'] = $Pockets;
                    
                    if (isset($Rule['Override']['Host']))
                    {
                        $Call['HTTP']['Override']['Host'] = $_SERVER['HTTP_HOST'];
                        $_SERVER['HTTP_HOST'] = $Rule['Override']['Host'];
                    }
                    
                    if (isset($Rule['Override']['URI']))
                    {
                        $Call['HTTP']['Override']['URI'] = $_SERVER['REQUEST_URI'];
                        $_SERVER['REQUEST_URI'] = $Rule['Override']['URI'];
                    }
                        
                    break;
                }
            }
        }
        
        return $Call;
    });