<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Zones.Get', function ($Call)
    {
        return timezone_identifiers_list();
    });
    
    setFn('Offsets.Get', function ($Call)
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
    
    setFn('Offset.GetByIdentifier', function ($Call)
    {
        $Zone = new DateTimeZone($Call['Value']);
        return $Zone->getOffset(new DateTime());
    });