<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.0
     */

    setFn('Parse', function ($Call)
    {
        $Queries = [];
        foreach ($Call['Parsed'][2] as $Ix => $Match)
        {
            if (preg_match('@^(.+)\:(.+)\:(.+)$@SsUu', $Match, $Slices))
            {
                list(,$Entity, $ID, $Field) = $Slices;
                $Queries[$Entity]['IDs'][$ID] = $ID;
                $Queries[$Entity]['Fields'][$Field] = $Field;
            }
        }

        foreach($Queries as $Entity => $KV)
        {
            $KV['Fields']['ID'] = 'ID';

            $Elements = F::Run('Entity', 'Read',
                        [
                            'Entity' => $Entity,
                            'Where'  => $KV['IDs'],
                            'Fields' => $KV['Fields']
                        ]);

            foreach ($Elements as $Element)
                foreach ($Element as $Key => $Value)
                    $Call['Output'] = str_replace('<far>'.$Entity.':'.$Element['ID'].':'.$Key, $Value, $Call['Output']);
        }

        // $Call['Output'] = str_replace($Call['Parsed'][0][$Ix], '' . $Match, $Call['Output']);
        /*

                    if (!empty($Element))
                        $Call['Output'] =
                            str_replace($Call['Parsed'][0][$Ix], F::Dot($Element, $Key),$Call['Output']);
                    else
                        $Call['Output'] =
                            str_replace($Call['Parsed'][0][$Ix], '' . $ID, $Call['Output']);*/

        return $Call;
     });