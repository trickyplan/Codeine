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

            foreach($Queries as $Entity => $KV)
            {
                $KV['Fields']['ID'] = 'ID';

                $Elements = F::Run('Entity', 'Read',
                            [
                                'Entity' => $Entity,
                                'Where'  => ['ID' => $KV['IDs']],
                                'Fields' => $KV['Fields']
                            ]);

                if (empty($Elements))
                    ;
                else
                    foreach ($Elements as $Element)
                        foreach ($Element as $Key => $Value)
                            if (is_scalar($Value))
                                $Call['Output'] = str_replace('<far>'.$Entity.':'.$Element['ID'].':'.$Key.'</far>', $Value, $Call['Output']);
                            else
                                $Call['Output'] = str_replace('<far>'.$Entity.':'.$Element['ID'].':'.$Key.'</far>', array_shift($Value), $Call['Output']);
            }
        }

        return $Call;
     });