<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Get Zones', function ($Call)
    {
        return timezone_identifiers_list();
    });
    
    setFn('Get Offsets', function ($Call)
    {
        $Offsets = [];
        $Abbreviations = timezone_abbreviations_list();
        foreach($Abbreviations as $Continent)
            foreach ($Continent as $Zone)
                $Offsets[$Zone['timezone_id']] = $Zone['offset'];
        
        unset($Offsets['']);
        ksort($Offsets);
        return $Offsets;
    });
    
    setFn('Get Offset By Identifier', function ($Call)
    {
        $Zone = new DateTimeZone($Call['Identifier']);
        return $Zone->getOffset(new DateTime());
    });