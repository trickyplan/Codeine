<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Get', function ($Call)
    {
        $Filename = '/Assets/'.strtr($Call['Asset'], '.', DS).DS.$Call['ID'].'.'.$Call['Extension'];
        $Call['Output']['Content'] = F::findFile($Filename);
        
        if ($Call['Output']['Content'] === null)
        {
            $Call['HTTP']['Headers']['HTTP/1.0'] = '404 Not Found';
            F::Log('Asset not found: '. $Filename, LOG_INFO);
            $Call['HTTP']['Headers']['Filename:'] = $Filename;
        }
        
        return $Call;
    });