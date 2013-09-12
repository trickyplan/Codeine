<?php

    /* Codeine
     * @author BreathLess
     * @description Media includes support 
     * @package Codeine
     * @version 7.x
     */

    setFn('Process', function ($Call)
    {
        if (preg_match('/<place>JS<\/place>/SsUu', $Call['Output']))
        {
            if (preg_match_all('/<jsrun>(.*)<\/jsrun>/SsUu', $Call['Output'], $Parsed))
            {
                $JSInline = implode(';', $Parsed[1]);
                $Call['Output'] = str_replace($Parsed[0], '', $Call['Output']);
            }
            else
                $JSInline = '';

            $Parsed = F::Run('Text.Regex', 'All',
                [
                    'Pattern' => $Call['JS']['Pattern'],
                    'Value' => $Call['Output']
                ]);

            if ($Parsed)
            {
                $Call['JS']['Input'] = $Parsed[1];

                $Call = F::Hook('beforeJSInput', $Call);

                // JS Input
                foreach ($Call['JS']['Input'] as $JS)
                {
                    list($Asset, $ID) = F::Run('View', 'Asset.Route', ['Value' => $JS]);

                    $Call['JS']['Scripts'][$JS] = F::Run('IO', 'Read',
                        [
                            'Storage' => 'JS',
                            'Scope'   => [$Asset, 'js'],
                            'Where'   => $ID
                        ])[0];

                    if ($Call['JS']['Scripts'][$JS])
                        F::Log('JS loaded: '.$JS, LOG_DEBUG);
                    else
                        F::Log('JS cannot loaded: '.$JS, LOG_WARNING);
                }

                $Call['JS']['Scripts'][] = $JSInline;

                $Call = F::Hook('afterJSInput', $Call);

                $Call = F::Hook('beforeJSOutput', $Call);

                // JS Output
                if (isset($Call['JS']['Host']) && !empty($Call['JS']['Host']))
                    $Host = $Call['JS']['Host'];
                else
                    $Host = $Call['RHost'];

                foreach ($Call['JS']['Scripts'] as $JS => $JSSource)
                {
                    $JS = sha1($JSSource).'_'.$JS;

                    if ($Call['JS']['Caching'] && F::Run('IO', 'Execute',
                        [
                            'Storage' => 'JS Cache',
                            'Scope'   => [$Host, 'js'],
                            'Execute' => 'Exist',
                            'Where'   =>
                            [
                                'ID' => $JS
                            ]
                        ]))
                    {
                        F::Log('Cache *hit*', LOG_GOOD);
                    }
                    else
                    {
                        $Call = F::Hook('beforeJSWrite', $Call);

                            F::Log('Cache *miss* *'.$JS.'*', LOG_BAD);

                            F::Run ('IO', 'Write',
                            [
                                 'Storage' => 'JS Cache',
                                 'Scope'   => [$Host, 'js'],
                                 'Where'   => $JS,
                                 'Data' => $JSSource
                            ]);

                        $Call = F::Hook('afterJSWrite', $Call);
                    }


                    if (isset($Call['JS']['Host']) && !empty($Call['JS']['Host']))
                        $Call['JS']['Links'][] = '<script src="'
                            .$Call['JS']['Proto']
                            .$Call['JS']['Host']
                            .$Call['JS']['Pathname']
                            .$JS
                            .$Call['JS']['Extension'].'" type="'.$Call['JS']['Type'].'"></script>';
                    else
                        $Call['JS']['Links'][]
                            = '<script src="'.$Call['JS']['Pathname'].$JS.$Call['JS']['Extension'].'" type="'.$Call['JS']['Type'].'"></script>';
                }

                $Call = F::Hook('afterJSOutput', $Call);

                $Call['Output'] = str_replace('<place>JS</place>', implode(PHP_EOL, $Call['JS']['Links']), $Call['Output']);
            }

            $Call['Output'] = str_replace($Parsed[0], '', $Call['Output']);
        }

        return $Call;
    });