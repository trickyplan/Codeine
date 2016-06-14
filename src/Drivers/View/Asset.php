<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Get', function ($Call)
    {
        $Call['Output']['Content'] = F::findFile('/Assets/'.strtr($Call['Asset'], '.', DS).DS.$Call['Scope'].DS.$Call['ID'].'.'.$Call['Extension']);
        return $Call;
    });