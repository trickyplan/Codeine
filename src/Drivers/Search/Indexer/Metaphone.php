<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Get', function ($Call)
    {
        $Index = [];


        foreach ($Call['Nodes'] as $Name => $Node)
        {
            if (isset($Node['Index']) && $Node['Index'])
            {
                $Call['Data'][$Name] = preg_replace('/<[\/\!]*?[^<>]*?>/si', '.', $Call['Data'][$Name]);
                if (preg_match_all('/([a-zA-Zа-яА-ЯёЁ]+)/Ssu', $Call['Data'][$Name], $Pockets))
                {
                    foreach($Pockets[1] as $Pocket)
                    {
                        $IDX = F::Run('Text.Transform.Transliterate.Passport', '2English', ['Value' => F::Run('Text.Index.Metaphone.Russian', 'Get', ['Value' => mb_strtolower($Pocket)])]);

                        if (strlen($IDX)>2)
                            $Index[] = $IDX;
                    }
                }
            }
        }

        $Index = array_unique($Index); // TODO Relevancy
        sort($Index);

        return $Index;
    });