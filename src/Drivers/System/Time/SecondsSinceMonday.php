<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Get', function ($Call)
    {
        $WeekStart =  new DateTime ('midnight last monday');
        return time() - $WeekStart->getTimestamp();
    });