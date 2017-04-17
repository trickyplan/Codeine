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
            if (empty($Call['Parsed']['Options'][$IX]))
                $Template = 'Tag';
            else
                $Template = F::Dot($Call['Parsed'],'Options.'.$IX.'.template') ? F::Dot($Call['Parsed'],'Options.'.$IX.'.template'): 'Tag';

            if (preg_match('@^(.+)\:(.+)$@SsUu', $Call['Parsed']['Value'][$IX], $Slices))
            {
                list(,$Entity, $ID) = $Slices;
                
                if (empty($ID))
                    $Replaces[$IX] = '';
                else
                {
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
                                      'Scope'  => $Entity,
                                      'ID'     => 'Show/'.$Template,
                                      'Data'   => $Element
                                ]);
                    
                }
                
            }
            else
                $Replaces[$IX] = '';
        }

        return $Replaces;
     });