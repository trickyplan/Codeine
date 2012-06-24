<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn('Generate', function ($Call)
    {
        $Call['Value'] = '';

        if (is_array($Call['Key']))
        {
            foreach ($Call['Key'] as $cKey)
            {
                $Call['Data'][$cKey] = trim($Call['Data'][$cKey]);
                if (!empty($Call['Data'][$cKey]))
                    $Call['Value'][]= $Call['Data'][$cKey];
            }

            $Call['Value'] = implode($Call['Delimiter'], $Call['Value']);
        }
        else
            $Call['Value'] = trim($Call['Data'][$Call['Key']]);

        if (empty($Call['Value']))
            return null;

        $Call['Value'] = mb_strtolower($Call['Value']);

        $Call['Value'] = strtr($Call['Value'], ' ', $Call['Delimiter']);

        if (isset($Call['Transliteration']))
            $Call['Value'] = F::Run($Call['Transliteration']['Service'], $Call['Transliteration']['Method'], array ('Value' => $Call['Value']));

        $Call['Value'] = preg_replace('/([^a-z0-9\-])/', '', $Call['Value']); // FIXME

        return $Call['Value'];
    });