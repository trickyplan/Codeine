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
                $Tags = [];
                foreach ($Queue as $Parslet)
                    $Tags[strtolower($Parslet)] = strtolower($Parslet).($Pass > 1? $Pass : '');

                $Tags = implode('|', $Tags);
                $Patterns[$Pass][] = '<(' . $Tags . ') (.*?)>(.*?)</(\1)>';
                $Patterns[$Pass][] = '<(' . $Tags . ')()>(.*?)</(\1)>';
                $Pass++;
            }
            // Парсим в глубину
            $Pass = 1;

            while(true)
            {
                $Variants = $Patterns[$Pass];
                $Assoc = [];

                foreach ($Variants as $Pattern)
                {
                    $Parsed = F::Run('Text.Regex', 'All',
                        [
                            'Pattern' => $Pattern,
                            'Value' => $Call['Output']
                        ]);
                    $Parsed = is_array($Parsed) ? $Parsed : [0, []];

                    foreach ($Parsed[1] as $Key => $Parslet)
                    {
                        $Tag = strtolower(
                            $Pass > 1 ? substr($Parslet, 0, -strlen($Pass)) : $Parslet
                        );
                        $Assoc[$Tag][0][] = $Parsed[0][$Key];
                        $Assoc[$Tag][1][] = $Parsed[2][$Key];
                        $Assoc[$Tag][2][] = $Parsed[3][$Key];
                    }
                }

                if ($Assoc === [] && $Pass == $MaxPass)
                    break;

                foreach ($Queue as $Parslet)
                {
                    $Tag = strtolower($Parslet);

                    if (!isset($Assoc[$Tag]))
                        continue;

                    $Call['Parsed'] = $Assoc[$Tag];
                    $Call = F::Apply('View.HTML.Parslets.' . $Parslet, 'Parse', $Call);
                    F::Log('Parslet *' . $Parslet . '* processed', LOG_DEBUG);
                }

                $Pass++;

                if ($Assoc !== [] && $Pass > 1)
                    $Pass = 1;
            }
        }
        F::Log('*End* parslets processing', LOG_INFO);
        return $Call;
    });