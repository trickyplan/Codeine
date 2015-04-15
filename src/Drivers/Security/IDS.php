<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Check', function ($Call)
    {
        $Filters = F::Run('IO', 'Read',
            [
                'Storage' => 'Web',
                'Format' => 'Formats.JSON',
                'RTTL'    => 86400,
                'Where'   =>
                [
                    'ID' => $Call['IDS']['Filters URL']
                ]
            ]
        );

        $Filters = array_pop($Filters);

        $Score = 0;

        F::Log('*'.count($Filters['filters']['filter']).'* filters loaded', LOG_INFO, 'Security');

        foreach ($Filters['filters']['filter'] as $Filter)
            if (preg_match('/'.$Filter['rule'].'/Ssu', $Call['HTTP']['URI']))
            {
                F::Log('IDS-'.$Filter['id'].': '.$Filter['description'].' with impact '.$Filter['impact'], LOG_NOTICE - $Filter['impact'], 'Security');
                $Score += $Filter['impact'];
            }

        $Verbose = LOG_INFO;

        if ($Score > $Call['IDS']['Impact Levels']['Red'])
        {
            $Call = F::Hook('onRedImpactLevel', $Call);
            $Verbose = LOG_ERR;
        }
        elseif ($Score > $Call['IDS']['Impact Levels']['Yellow'])
        {
            $Call = F::Hook('onYellowImpactLevel', $Call);
            $Verbose = LOG_WARNING;
        }

        F::Log('Overall IDS Impact: '.$Score, $Verbose, 'Security');
        return $Call;
    });