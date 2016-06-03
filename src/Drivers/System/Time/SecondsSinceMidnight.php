<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Get', function ($Call)
    {
        $DayStart =  new DateTime ('midnight today');
        return time() - $DayStart->getTimestamp();
    });