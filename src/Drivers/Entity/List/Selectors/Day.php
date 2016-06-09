<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        $DayStart =  new DateTime ('midnight today');
        $DayFinish= new DateTIme ('midnight tomorrow');

        return  F::Merge($Call['Where'], [
                    $Call['Selector']['Day']['Key'] =>
                    [
                        '$gte' => $DayStart->getTimestamp() + ($Call['Selector']['Day']['Increment']*86400),
                        '$lt' => $DayFinish->getTimestamp() + ($Call['Selector']['Day']['Increment']*86400)
                    ]
                ]);
    });