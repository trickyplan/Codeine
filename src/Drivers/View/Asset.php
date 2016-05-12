<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Get', function ($Call)
    {
        $Call['Output']['Content'] = F::findFile('/Assets/'.$Call['Asset'].'/'.$Call['Scope'].'/'.$Call['ID'].'.'.$Call['Extension']);
        return $Call;
    });