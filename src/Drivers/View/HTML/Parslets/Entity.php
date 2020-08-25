<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 7.0
     */

    setFn('Parse', function ($Call)
    {
        $Replaces = [];
       
        foreach ($Call['Parsed']['Value'] as $IX => $Match)
        {
            if (preg_match('@^(.+)\:(.+)$@SsUu', $Call['Parsed']['Value'][$IX], $Slices))
            {
                list(,$Entity, $ID) = $Slices;

                if (empty($ID))
                    $Replaces[$IX] = '';
                else
                {
                    $Scope      = $Entity.'/'.(F::Dot($Call['Parsed'],'Options.'.$IX.'.scope') ? F::Dot($Call['Parsed'],'Options.'.$IX.'.scope'): 'Show');
                    $Template   = F::Dot($Call['Parsed'],'Options.'.$IX.'.template') ? F::Dot($Call['Parsed'],'Options.'.$IX.'.template'): 'Tag';

                    $Element = F::Run('Entity', 'Read',
                    [
                          'Entity' => $Entity,
                          'Where'  => $ID,
                          'One'    => true
                    ]);
                
                    if (empty($Element))
                        $Replaces[$IX] = '';
                    else
                        $Replaces[$IX] = F::Run('View', 'Load', $Call,
                                [
                                      'Scope'  => $Scope,
                                      'ID'     => $Template,
                                      'Data'   => $Element
                                ]);
                    
                }
                
            }
            else
                $Replaces[$IX] = '';
        }

        return $Replaces;
     });