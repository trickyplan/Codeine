<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Get', function ($Call)
    {
        $Token = $Call['Data']['Target']['Type'].'.Journal:'.$Call['Data']['Action'];
        $Message = F::Run('Locale', 'Get', $Call, ['Message' => $Token]);
        
        $Call['Source'] = F::Run('Entity', 'Read',
            [
                'Entity'    => $Call['Data']['Source']['Type'],
                'Where'     => $Call['Data']['Source']['ID'],
                'One'       => true
            ]);
        
        $Call['Target'] = F::Run('Entity', 'Read',
            [
                'Entity'    => $Call['Data']['Target']['Type'],
                'Where'     => $Call['Data']['Target']['ID'],
                'One'       => true
            ]);
        
        if ($Message === null and F::Environment() === 'Development')
            $Message = '<l>'.$Token.'</l>';
        else
            $Message = F::Live($Message, $Call);
        
        return $Message;
    });