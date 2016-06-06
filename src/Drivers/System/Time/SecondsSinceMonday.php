<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Get', function ($Call)
    {
        $WeekStart =  new DateTime ('midnight Monday this week');
        return time() - $WeekStart->getTimestamp();
    });