<?php

    /* Codeine
     * @author bergstein@trickyplan.com
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


        if (empty($Queries))
            ;
        else
        {
            $Elements = [];

            foreach($Queries as $Entity => $KV)
            {
                $KV['Fields']['ID'] = 'ID';

                sort($KV['IDs']);

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
            }

            if (empty($Elements))
                ;
            else
                foreach ($Call['Parsed'][2] as $IX => $Match)
                    if (preg_match('@^(.+)\:(.+)\:(.+)$@SsUu', $Match, $Slices))
                    {
                        unset($Slices[0]);
                        $Call['Output'] = str_replace($Call['Parsed'][0][$IX], F::Dot($Elements, implode('.', $Slices)), $Call['Output']);
                    }
        }

        return $Call;
     });