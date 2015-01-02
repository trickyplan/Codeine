<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Make', function ($Call)
    {
        $Output = '';

        foreach ($Call['Value'] as $Row)
        {
            if (isset($Row['_Class']))
            {
                if ($Row['_Class'] == 'danger')
                    $Output .= "\033[0;31m";

                if ($Row['_Class'] == 'success')
                    $Output .= "\033[0;32m";

                unset($Row['_Class']);
            }

            foreach ($Row as $Key => $Cell)
                $Output .= $Cell."\t";

            $Output .= " \033[0m";


            $Output.= "\n";
        }

        $Call['Value'] = $Output."\n";

        return $Call;
    });