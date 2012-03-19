<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Generate', function ($Call)
    {
        $Call['Value'] = '';

        if (is_array($Call['Key']))
        {
            foreach ($Call['Key'] as $cKey)
                if (!empty($Call['Data'][$cKey]))
                    $Call['Value'][]= $Call['Data'][$cKey];

            $Call['Value'] = implode($Call['Delimiter'], $Call['Value']);
        }
        else
            $Call['Value'] = $Call['Data'][$Call['Key']];

        $Call['Value'] = mb_strtolower($Call['Value']);

        $Call['Value'] = strtr($Call['Value'], ' ', $Call['Delimiter']);

        if (isset($Call['Transliteration']))
            $Call['Value'] = F::Run($Call['Transliteration']['Service'], $Call['Transliteration']['Method'], array ('Value' => $Call['Value']));

        return $Call['Value'];
    });