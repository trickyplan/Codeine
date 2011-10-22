<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::Fn ('Template', function ($Call)
        {
            $Call['Codeine'] = Codeine;

            $Script = file_get_contents(F::Find('Options/Project/Generator/Template.json'));

            if (preg_match_all('@\$(\w+)@', $Script, $Pockets))
                foreach ($Pockets[1] as $IX => $Match)
                    $Script = str_replace($Pockets[0][$IX], $Call[$Match], $Script);

            $Script = json_decode($Script, true);

            foreach ($Script as $Step)
                 F::Run($Step);

            return $Call;
        });