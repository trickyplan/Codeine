<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Get', function ($Call)
    {
        $Index = [];

        foreach ($Call['Nodes'] as $Name => $Node)
        {
            if (isset($Node['Index']) && $Node['Index'])
            {
                if (preg_match_all('/([a-zA-Zа-яА-Я0-9]+)/Ssu', strip_tags($Call['Data'][$Name]), $Pockets))
                {
                    foreach($Pockets[1] as $Pocket)
                        $Index[] =
                            F::Run('Text.Transform.Transliterate.Passport', '2English', ['Value' => F::Run('Text.Index.Metaphone.Russian', 'Get', ['Value' => mb_strtolower($Pocket)])]);
                }
            }
        }

        return array_unique($Index); // TODO Relevancy
    });