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
        
        foreach ($Call['Parsed']['Value'] as $IX => $Key)
        {
            if (mb_strpos($Key, ',') !== false)
                $Key = explode(',', $Key);
            else
                $Key = [$Key];

            $Value = '';

            foreach ($Key as $CMatch)
            {
                $Value = F::Live(F::Dot($Call['Data'], $CMatch));

                if ($Value === null)
                {
                    if (isset($Call['Parsed']['Options'][$IX]['null']))
                        $Value = $Call['Parsed']['Options'][$IX]['null'];
                    else
                        $Value = 'null';
                    
                }
                else
                {
                    if ((array) $Value === $Value)
                        $Value = array_shift($Value);

                    if ($Value === 0)
                        $Value = '0';
                    
                    if ($Value === false)
                        $Value = 'false';
                    
                    if ($Value === true)
                        $Value = 'true';
                    
                    break;
                }
            }

            if (is_array($Value))
                $Value = array_pop($Value);
            
            $Replaces[$IX] = $Value;
        }

        return $Replaces;
     });