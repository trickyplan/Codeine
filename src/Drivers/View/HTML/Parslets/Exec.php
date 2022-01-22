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
            if (F::Dot($Call['Parsed'],'Options.'.$IX.'.format') === null)
            {
                if (F::Dot($Call['Parsed'],'Options.'.$IX.'.type') === null)
                    $Format = $Call['Parslet']['Exec']['Type'];
                else
                {
                    $Format = F::Dot($Call['Parsed'],'Options.'.$IX.'.type');

                    F::Log('Please, replace "type" with "format" as more semantically correct', LOG_WARNING, ['Developer', 'Deprecated']);
                    F::Log($Match, LOG_WARNING, ['Developer', 'Deprecated']);
                }
            }
            else
                $Format = F::Dot($Call['Parsed'],'Options.'.$IX.'.format');

            $Match = F::Run('Formats.' . $Format, 'Read', ['Value' => trim($Call['Parsed']['Value'][$IX])]);
            
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
                
                $Application = F::Run('Code.Flow.Application', 'Run', ['RTTL' => $RTTL, 'Run' => $Match, 'Started' => Started]);
                
                /*if (F::Environment() == 'Development')
                    $Application['Output'] = '<div class="exec-cached">'.$Application['Output'].'</div>';
                */
                
                if (isset($Application['Output']))
                {
                    if (is_scalar($Application['Output']))
                        $Replaces[$Call['Parsed']['Match'][$IX]] = $Application['Output'];
                    else
                    {
                        $Replaces[$Call['Parsed']['Match'][$IX]] = '{}';
                        F::Log('Application Output isn\'t scalar', LOG_ERR);
                        F::Log($Application['Output'], LOG_WARNING);
                    }
                }
                else
                    $Replaces[$Call['Parsed']['Match'][$IX]] = '';
                
            } else
                $Replaces[$Call['Parsed']['Match'][$IX]] = '[Bad Exec]';
        }
        
        return $Replaces;
    });