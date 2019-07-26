<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Write', function ($Call)
    {
        $OutputLog = '';

        if (is_array($Call['Value']))
        {
            $LastRow = ['M' => 0];
            $HeaderRequired = true;

            foreach ($Call['Value'] as $IX => $Row)
            {
                if ($HeaderRequired)
                {
                    $OutputLog.= '|'.implode('|',
                    [
                        'V',
                        ':watch:',
                        'Mem',
                        'Hash',
                        'Source',
                        'Message'
                    ]).'|'.PHP_EOL
                    .'|'.implode('|',
                    [
                        ':-:',
                        ':-:',
                        ':-:',
                        ':-:',
                        ':-',
                        ':-'
                    ]).'|'.PHP_EOL;
                    $HeaderRequired = false;
                }

                $Row['M'] = round($Row['M']/1024);
                $MemoryDiff = $Row['M'] - $LastRow['M'];

                if ($MemoryDiff > 0)
                    $MemoryDiff = '+'.$MemoryDiff;

                $OutputLog .= '|'.implode('|',
                    [
                        'V'.$Row['V'],
                        $Row['T'],
                        $MemoryDiff,
                        $Row['H']?? '',
                        ($Row['R'] == (isset($Call['Value'][$IX-1]['R'])? $Call['Value'][$IX-1]['R']: false)? '': $Row['R'].' from '.$Row['I']),
                        $Row['X']
                    ]).'|'.PHP_EOL;

/*                if (isset($Row['K']))
                {
                    $OutputLog .= PHP_EOL.'```json '.PHP_EOL.$Row['K'].PHP_EOL.'```'.PHP_EOL;
                    $HeaderRequired = true;
                }*/

                $LastRow = $Row;
            }
            $OutputLog .= '';
        }
        else
            $OutputLog = $Call['Value'];

        if (F::Dot($Call, 'Log.Asterisk'))
            $OutputLog = preg_replace('/\*(.*)\*/SsUu', '***$1***', $OutputLog);

        $Output = $OutputLog;

        return $Output;
    });