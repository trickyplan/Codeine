<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Exec Parslet 
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Parse', function ($Call)
    {
        $Replaces = [];
        
        foreach ($Call['Parsed']['Value'] as $IX => $Match)
        {
            if (F::Dot($Call['Parsed'],'Options.'.$IX.'.type') === null)
                $Type = $Call['Parslet']['Exec']['Type'];
            else
                $Type = F::Dot($Call['Parsed'],'Options.'.$IX.'.type');
            
            $Match = F::Run('Formats.' . $Type, 'Read', ['Value' => trim($Call['Parsed']['Value'][$IX])]);
            
            if ($Match)
            {
                F::Log($Match, LOG_INFO);
                
                foreach ($Call['Parslet']['Exec']['Inherited'] as $Key)
                    if (isset($Call[$Key]))
                        $Match[$Key] = $Call[$Key];
                    else
                        $Match[$Key] = null;
                
                if (isset($Match['Exec TTL']))
                    $RTTL = $Match['Exec TTL'];
                else
                    $RTTL = 0;
                
                $Application = F::Run('Code.Flow.Application', 'Run', ['RTTL' => $RTTL, 'Run' => $Match]);
                
                /*if (F::Environment() == 'Development')
                    $Application['Output'] = '<div class="exec-cached">'.$Application['Output'].'</div>';
                */
                
                if (isset($Application['Output']))
                {
                    if (is_scalar($Application['Output']))
                        $Replaces[$IX] = $Application['Output'];
                    else
                    {
                        $Replaces[$IX] = '{}';
                        F::Log('Application Output isn\'t scalar', LOG_ERR);
                        F::Log($Application['Output'], LOG_WARNING);
                    }
                }
                else
                    $Replaces[$IX] = '';
                
            } else
                $Replaces[$IX] = '[Bad Exec]';
        }
        
        return $Replaces;
    });