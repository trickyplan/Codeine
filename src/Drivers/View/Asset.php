<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Get', function ($Call)
    {
        $Call['HTTP']['Headers']['Access-Control-Allow-Origin:'] = $Call['HTTP']['Proto'].$Call['HTTP']['Host'];
        $Filename = '/Assets/'.strtr($Call['Asset'], '.', DS).DS.$Call['ID'].'.'.$Call['Extension'];
        $Call['Output']['Content'] = F::findFile($Filename);
        
        if ($Call['Output']['Content'] === null)
        {
            $Call['HTTP']['Headers']['HTTP/1.1'] = '404 Not Found';
            F::Log('Asset not found: '. $Filename, LOG_INFO);
            $Call['HTTP']['Headers']['Filename:'] = $Filename;
        }
        
        return $Call;
    });