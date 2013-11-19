<?php

/* Codeine
 * @author BreathLess
 * @description
 * @package Codeine
 * @version 7.x
 */

    setFn('Do', function ($Call) {
        $Output = '
               <table class="table console">
               <thead>
               <tr>
                    <th>Function name</th>
                    <th>Time, %</th>
                    <th>Calls, %</th>
                    <th>Time, ms</th>
                    <th>Calls, count</th>
                    <th>Time per call, ms</th>
               </tr>
               <tr class="Important">
                   <th>' . $Call['Host'] . $Call['URI'] . '</th>
                   <th>100%</th>
                   <th>100%</th>
                   <th>' . round($Call['Profile']['Summary']['Time']) . '</th>
                   <th>' . $Call['Profile']['Summary']['Calls'] . '</th>
                   <th>' . round($Call['Profile']['Summary']['Time'] / $Call['Profile']['Summary']['Calls'], 2) . '</th>
               </tr>
               </thead>';

        foreach ($Call['Data']['T'] as $Key => $Value)
        {
            if (!isset($Call['Data']['C'][$Key]))
                $Call['Data']['C'][$Key] = 1;

            $Class =
                [
                    'ATime' => 'Good',
                    'RTime' => 'Good',
                    'ACalls' => 'Good',
                    'RCalls' => 'Good',
                    'TimePerCall' => 'Good'
                ];

            $Call['RTime'] = round(($Value / $Call['Profile']['Summary']['Time']) * 100, 2);
            $Call['RCalls'] = round(($Call['Data']['C'][$Key] / $Call['Profile']['Summary']['Calls']) * 100, 2);
            $Call['ATime'] = round($Value);
            $Call['ACalls'] = $Call['Data']['C'][$Key];
            $Call['TimePerCall'] = round($Value / $Call['Data']['C'][$Key], 2);

            if (isset($Call['Alerts']['Yellow']))
                foreach ($Call['Alerts']['Yellow'] as $Metric => $Limit)
                    if ($Call[$Metric] > $Limit)
                        $Class[$Metric] = 'Warning';

            if (isset($Call['Alerts']['Red']))
                foreach ($Call['Alerts']['Red'] as $Metric => $Limit)
                    if ($Call[$Metric] > $Limit)
                        $Class[$Metric] = 'Error';

            $Output .= '<tr>
                <td>' . $Key . '</td>' .
                '<td class="' . $Class['RTime'] . '">' . $Call['RTime'] . '</td>' .
                '<td class="' . $Class['RCalls'] . '">' . $Call['RCalls'] .
                '<td class="' . $Class['ATime'] . '">' . $Call['ATime'] . '</td>' .
                '<td class="' . $Class['ACalls'] . '">' . $Call['ACalls'] . '</td>' .
                '<td class="' . $Class['TimePerCall'] . '">' . $Call['TimePerCall'] . '</td>' .
                '</td></tr>';
        }

        $Output .= '</table>';

        return $Output;
    });