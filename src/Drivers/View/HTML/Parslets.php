<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Apriori Parser 
     * @package Codeine
     * @version 8.x
     */

    setFn('Process', function ($Call)
    {
        if (isset($Call['Output']))
        {
            F::Log('*Start* parslets processing', LOG_INFO);
            // Собираем паттерны
            $Pass = 1;
            $Patterns = [];
            $MaxPass = $Call['View']['HTML']['Parslets']['Max Passes'];
            $Queue = $Call['View']['HTML']['Parslets']['Queue'];

            while($Pass <= $MaxPass)
            {
                $tags = [];
                foreach ($Queue as $Parser)
                {
                    $tags[strtolower($Parser)] = strtolower($Parser).($Pass > 1? $Pass : '');
                }

                $tags = implode('|', $tags);
                $Patterns[$Pass][] = '<(' . $tags . ') (.*?)>(.*?)</(\1)>';
                $Patterns[$Pass][] = '<(' . $tags . ')()>(.*?)</(\1)>';
                $Pass++;
            }
            // Парсим в глубину
            $Pass = 1;
            while( true )
            {
                $Variants = $Patterns[$Pass];
                $Assoc = [];

                foreach ($Variants as $Pattern) {
                    $Parsed = F::Run('Text.Regex', 'All',
                        [
                            'Pattern' => $Pattern,
                            'Value' => $Call['Output']
                        ]);
                    $Parsed = is_array($Parsed) ? $Parsed : [0, []];

                    foreach ($Parsed[1] as $key => $Parser) {
                        $ind = strtolower(
                            $Pass > 1 ? substr($Parser, 0, -strlen($Pass)) : $Parser
                        );
                        $Assoc[$ind][0][] = $Parsed[0][$key];
                        $Assoc[$ind][1][] = $Parsed[2][$key];
                        $Assoc[$ind][2][] = $Parsed[3][$key];
                    }
                }

                if ($Assoc === [] && $Pass == $MaxPass)
                    break;

                foreach ($Queue as $Parser) {
                    $ind = strtolower($Parser);
                    if (!isset($Assoc[$ind]))
                        continue;
                    $Call['Parsed'] = $Assoc[$ind];
                    $Call = F::Apply('View.HTML.Parslets.' . $Parser, 'Parse', $Call);
                    F::Log('Parslet *' . $Parser . '* processed', LOG_DEBUG);
                }

                $Pass++;

                if ($Assoc !== [] && $Pass > 1)
                    $Pass = 1;
            }
        }
        F::Log('*End* parslets processing', LOG_INFO);
        return $Call;
    });