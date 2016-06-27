<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Get', function ($Call)
    {
        if (date('N') == 1)
            $WeekStart =  new DateTime ('midnight'); // Fuck you, PHP egocentric WASPs
        else
            $WeekStart =  new DateTime ('midnight next Monday -1 week'); // Fuck you, PHP egocentric WASPs

        return time() - $WeekStart->getTimestamp();
    });