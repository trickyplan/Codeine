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
        $Filename = '/Assets/'.strtr($Call['Asset'], '.', DS).DS.$Call['Scope'].DS.$Call['ID'].'.'.$Call['Extension'];
        $Call['Output']['Content'] = F::findFile($Filename);
        
        if ($Call['Output']['Content'] === null)
        {
            F::Log('Asset not found: '. $Filename, LOG_ERR);
        }
            
        return $Call;
    });