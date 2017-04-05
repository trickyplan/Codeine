<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 7.0
     */

    setFn('Parse', function ($Call)
    {
        foreach ($Call['Parsed']['Value'] as $Ix => $Match)
        {
            if (!empty($Call['Parsed']['Options'][$Ix]))
            {
                $Root = simplexml_load_string('<root '.$Call['Parsed']['Options'][$Ix].'></root>');
                $Template = isset($Root->attributes()->template)? (string) $Root->attributes()->template: 'Tag';
            }
            else
                $Template = 'Tag';

            if (preg_match('@^(.+)\:(.+)$@SsUu', $Call['Parsed']['Value'][$Ix], $Slices))
            {
                list(,$Entity, $ID) = $Slices;
                
                if (empty($ID))
                    $Call['Output'] = str_replace($Call['Parsed']['Match'][$Ix], '', $Call['Output']);
                else
                {
                    $Element = F::Run('Entity', 'Read',
                    [
                          'Entity' => $Entity,
                          'Where'  => $ID,
                          'One'    => true
                    ]);
                
                    if (!empty($Element))
                        $Call['Output'] = str_replace($Call['Parsed']['Match'][$Ix],
                            F::Run('View', 'Load', $Call,
                                [
                                      'Scope'  => $Entity,
                                      'ID'     => 'Show/'.$Template,
                                      'Data'   => $Element
                                ]),
                            $Call['Output']);
                    else
                        $Call['Output'] = str_replace($Call['Parsed']['Match'][$Ix], '', $Call['Output']);
                }
                
            }
            else
                $Call['Output'] = str_replace($Call['Parsed']['Match'][$Ix], '', $Call['Output']);

        }

        return $Call;
     });