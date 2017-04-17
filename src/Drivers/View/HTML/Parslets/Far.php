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
        $Queries = [];
        
        foreach ($Call['Parsed']['Value'] as $Ix => $Match)
        {
            if (preg_match('@^(.+)\:(.+)\:(.+)$@SsUu', $Match, $Slices))
            {
                list(,$Entity, $ID, $Field) = $Slices;
                $Queries[$Entity]['IDs'][$ID] = $ID;
                $Queries[$Entity]['Fields'][$Field] = $Field;
            }
        }

        if (empty($Queries))
            ;
        else
        {
            $Elements = [];

            foreach($Queries as $Entity => $KV)
            {
                $KV['Fields']['ID'] = 'ID';

                sort($KV['IDs']);
                $KV['IDs'] = array_unique($KV['IDs']);
            }
            
            $Loaded = F::Run('Entity', 'Read',
            [
                'Entity' => $Entity,
                'Where'  => ['ID' => ['$in' => $KV['IDs']]],
                'Fields' => $KV['Fields']
            ]);

            if (empty($Loaded))
                ;
            else
                foreach ($Loaded as $Element)
                    $Elements = F::Dot($Elements, $Entity.'.'.$Element['ID'], $Element);

            if (empty($Elements))
                ;
            else
                foreach ($Call['Parsed']['Value'] as $IX => $Match)
                    if (preg_match('@^(.+)\:(.+)\:(.+)$@SsUu', $Match, $Slices))
                    {
                        unset($Slices[0]);
                        $Replaces[$IX] = F::Dot($Elements, implode('.', $Slices));
                    }
        }

        return $Replaces;
     });