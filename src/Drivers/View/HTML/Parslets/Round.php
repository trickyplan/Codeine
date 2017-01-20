<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 7.0
     */

    setFn('Parse', function ($Call)
    {
        foreach ($Call['Parsed']['Match'] as $Ix => $Match)
        {
            $Round = simplexml_load_string($Match); // FIXME Абстрагировать этот пиздец
            $Call['Output'] = str_replace($Call['Parsed']['Match'][$Ix],round((float) $Round, (int) $Round->attributes()->precision), $Call['Output']);
        }

        return $Call;
     });