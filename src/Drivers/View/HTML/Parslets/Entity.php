<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.0
     */

    setFn('Parse', function ($Call)
    {
        foreach ($Call['Parsed'][2] as $Ix => $Match)
        {
            if (!empty($Call['Parsed'][1][$Ix]))
            {
                $Root = simplexml_load_string('<root '.$Call['Parsed'][1][$Ix].'></root>');
                $Template = isset($Root->attributes()->template)? (string) $Root->attributes()->template: 'Tag';
            }
            else
                $Template = 'Tag';


            if (preg_match('@^(.+)\:(.+)$@SsUu', $Call['Parsed'][2][$Ix], $Slices))
            {
                list(,$Entity, $ID) = $Slices;

                $Call['Locales'][] = $Entity;

                $Element = F::Run('Entity', 'Read',
                    array (
                          'Entity' => $Entity,
                          'Where'  => $ID));

                if (!empty($Element))
                    $Call['Output'] = str_replace($Call['Parsed'][0][$Ix],
                        F::Run('View', 'LoadParsed',
                            array (
                                  'Scope'  => $Entity,
                                  'ID'     => 'Show/'.$Template,
                                  'Data'   => $Element[0]
                            )),
                        $Call['Output']);
                else
                    $Call['Output'] = str_replace($Call['Parsed'][0][$Ix], '', $Call['Output']);
            }
            else
                $Call['Output'] = str_replace($Call['Parsed'][0][$Ix], '', $Call['Output']);

        }

        return $Call;
     });