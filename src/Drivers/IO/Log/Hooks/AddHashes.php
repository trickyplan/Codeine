<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('beforeLogSpit', function ($Call)
    {
        foreach ($Call['Channel Logs'] as &$Row)
        {
            if (isset($Row['X']))
            {
                if (is_scalar($Row['X']))
                    $Hash = $Row['X'];
                else
                    $Hash = serialize($Row['X']);
                
                $Row['H'] = mb_strtoupper(mb_substr(sha1($Row['I'].$Row['R'].$Hash), -12));
            }
        }
        
        return $Call;
    });