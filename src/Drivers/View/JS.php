<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Media includes support 
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
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
                foreach ($Call['JS']['Input'] as $Call['JS']['Fullpath'])
                {
                    if (preg_match('/^https?/SsUu', $Call['JS']['Fullpath']))
                    {
                        $JS2 = parse_url($Call['JS']['Fullpath'], PHP_URL_HOST).sha1($Call['JS']['Fullpath']);
                        $Call['JS']['Scripts'][$JS2] = F::Run('IO', 'Read',
                        [
                            'Storage' => 'Web',
                            'RTTL'    => $Call['JS']['Remote']['TTL'],
                            'Where'   =>
                            [
                                'ID' => $Call['JS']['Fullpath']
                            ]
                        ])[0];

                        $JS2 = strtr($JS2, '/', '-');
                        $Call['JS']['Fullpath'] = $JS2;
                    }
                    else
                    {
                        list($Asset, $ID) = F::Run('View', 'Asset.Route',
                            [
                                'Value' => $Call['JS']['Fullpath'],
                                'Scope' => 'js'
                            ]
                        );

                        $Call['JS']['Scripts'][$Call['JS']['Fullpath']] = F::Run('IO', 'Read',
                            [
                                'Storage' => 'JS',
                                'Scope'   => $Asset,
                                'Where'   => $ID
                            ])[0];
                    }

                    if ($Call['JS']['Scripts'][$Call['JS']['Fullpath']])
                        F::Log('JS is *loaded*: '.$Call['JS']['Fullpath'], LOG_DEBUG);
                    else
                        F::Log('JS *isn\'t* loaded*: '.$Call['JS']['Fullpath'], LOG_WARNING);
                }

                if (!empty($JSInline))
                {
                    $JSInline = $Call['JS']['Inline']['Prefix'].
                                $JSInline.
                                $Call['JS']['Inline']['Postfix'];
                    $Call['JS']['Scripts']['DomReady'] = $JSInline;
                }

                $Call = F::Hook('afterJSInput', $Call);

                $Call = F::Hook('beforeJSOutput', $Call);

                // JS Output
                foreach ($Call['JS']['Scripts'] as $Call['JS']['Fullpath'] => $Call['JS']['Source'])
                {
                    $Call['JS']['Fullpath'] = strtr($Call['JS']['Fullpath'], ':', '_').'_'.sha1($Call['JS']['Source']).$Call['JS']['Extension'];
                    $Write = true;

                    if ($Call['JS']['Caching'])
                    {
                        if (F::Run('IO', 'Execute', $Call,
                        [
                            'Storage' => 'JS Cache',
                            'Execute' => 'Exist',
                            'Where'   =>
                            [
                                'ID' => $Call['JS']['Fullpath']
                            ]
                        ]) === null)
                            F::Log('Cache *miss* *'.$Call['JS']['Fullpath'].'*', LOG_NOTICE);
                        else
                        {
                            F::Log('Cache *hit* '.$Call['JS']['Fullpath'], LOG_DEBUG);
                            $Write = false;
                        }
                    }

                    if ($Write)
                    {
                        $Call = F::Hook('beforeJSWrite', $Call);

                            F::Run ('IO', 'Write', $Call,
                            [
                                 'Storage' => 'JS Cache',
                                 'Where'   => $Call['JS']['Fullpath'],
                                 'Data' => $Call['JS']['Source']
                            ]);

                        $Call = F::Hook('afterJSWrite', $Call);
                    }

                    $SRC = F::Run('IO', 'Execute', $Call,
                    [
                        'Storage' => 'JS Cache',
                        'Execute' => 'Filename',
                        'Where'   => $Call['JS']['Fullpath']
                    ]);

                    if (isset($Call['JS']['Host']) && !empty($Call['JS']['Host']))
                        $JSFilename = $Call['HTTP']['Proto']
                                .$Call['JS']['Host']
                                .$SRC;
                    else
                        $JSFilename = $SRC;

                    $Call['JS']['Links'][$JSFilename] = '<script src="'.$JSFilename.'" type="'.$Call['JS']['Type'].'"></script>';
               }

                $Call = F::Hook('afterJSOutput', $Call);

                $Call['Output'] = str_replace('<place>JS</place>', implode(PHP_EOL, $Call['JS']['Links']), $Call['Output']);
            }

            $Call['Output'] = str_replace($Parsed[0], '', $Call['Output']);
        }

        unset($Call['JS']);

        return $Call;
    });