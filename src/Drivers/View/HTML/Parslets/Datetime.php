<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Exec Parslet 
     * @package Codeine
     * @version 6.0
     */
    
    setFn('Parse', function ($Call)
    {
        $Replaces = [];
        foreach ($Call['Parsed']['Value'] as $IX => $Match)
        {
            $Engine = F::Dot($Call['Parsed'],'Options.'.$IX.'.engine') ? F::Dot($Call['Parsed'],'Options.'.$IX.'.'.'engine'): 'Date';
            
            // TODO Due bug 13744 at w3c validator, time tag temporary diabled.
            // $Outer = '<time datetime="'.date(DATE_ISO8601, $Match).'">'.date($Format, $Inner).'</time>';
            
            $Date = ['Value' => $Match];
            
            if (F::Dot($Call['Parsed'],'Options.'.$IX.'.format'))
                $Date['Format'] = F::Dot($Call['Parsed'],'Options.'.$IX.'.format');
            
            $Date = F::Run('Formats.Date.' . $Engine, 'Format', $Date);
            
            $Replaces[$IX] = $Date;
        }
        
        return $Replaces;
    });