<?php

    /* Codeine
     * @author BreathLess
     * @description Media includes support 
     * @package Codeine
     * @version 8.x
     */

    setFn('Process', function ($Call)
    {
        if (preg_match('/<place>JS<\/place>/SsUu', $Call['Output']))
        {
            $Cache = F::Run('IO', 'Open', ['Storage' => 'JS Cache']);

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
                    if (preg_match('/^http:/SsUu', $Call['JS']['Fullpath']))
                    {
                        $JS2 = parse_url($Call['JS']['Fullpath'], PHP_URL_HOST).sha1($Call['JS']['Fullpath']);
                        $Call['JS']['Scripts'][$JS2] = F::Run('IO', 'Read',
                        [
                            'Storage' => 'Web',
                            'Where'   =>
                            [
                                'ID' => $Call['JS']['Fullpath']
                            ],
                            'IO TTL' => $Call['JS']['Remote']['TTL']
                        ])[0];
                        $Call['JS']['Fullpath'] = $JS2;
                    }
                    else
                    {
                        list($Asset, $ID) = F::Run('View', 'Asset.Route', ['Value' => $Call['JS']['Fullpath']]);

                        $Call['JS']['Scripts'][$Call['JS']['Fullpath']] = F::Run('IO', 'Read',
                            [
                                'Storage' => 'JS',
                                'Scope'   => [$Asset, 'js'],
                                'Where'   => $ID
                            ])[0];
                    }

                    if ($Call['JS']['Scripts'][$Call['JS']['Fullpath']])
                        F::Log('JS loaded: '.$Call['JS']['Fullpath'], LOG_INFO);
                    else
                        F::Log('JS cannot loaded: '.$Call['JS']['Fullpath'], LOG_WARNING);
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
                if (isset($Call['JS']['Host']) && !empty($Call['JS']['Host']))
                    $Host = $Call['JS']['Host'];
                else
                    $Host = $Call['HTTP']['Host'];

                foreach ($Call['JS']['Scripts'] as $Call['JS']['Fullpath'] => $Call['JS']['Source'])
                {
                    $Call['JS']['Fullpath'] = sha1($Call['JS']['Source']).'_'.strtr($Call['JS']['Fullpath'], ':', '_').$Call['JS']['Extension'];

                    $Call['JS']['Cached Filename'] = $Cache['Directory'].DS.$Call['HTTP']['Host'].DS.'js'.DS.$Call['JS']['Scope'].DS.$Call['JS']['Fullpath'];
                    $Write = true;

                    if ($Call['JS']['Caching'])
                    {
                        if (F::Run('IO', 'Execute',
                        [
                            'Storage' => 'JS Cache',
                            'Scope'   => [$Host, 'js'],
                            'Execute' => 'Exist',
                            'Where'   =>
                            [
                                'ID' => $Call['JS']['Fullpath']
                            ]
                        ]))
                        {
                            F::Log('Cache *hit* '.$Call['JS']['Fullpath'], LOG_GOOD);
                            $Write = false;
                        }
                        else
                        {
                            F::Log('Cache *miss* *'.$Call['JS']['Fullpath'].'*', LOG_BAD);
                        }
                    }

                    if ($Write)
                    {
                        $Call = F::Hook('beforeJSWrite', $Call);

                            F::Run ('IO', 'Write',
                            [
                                 'Storage' => 'JS Cache',
                                 'Scope'   => [$Host, 'js'],
                                 'Where'   => $Call['JS']['Fullpath'],
                                 'Data' => $Call['JS']['Source']
                            ]);

                        $Call = F::Hook('afterJSWrite', $Call);
                    }

                    if (isset($Call['JS']['Host']) && !empty($Call['JS']['Host']))
                        $JSFilename = $Call['HTTP']['Proto']
                                .$Call['JS']['Host']
                                .$Call['JS']['Pathname']
                                .$Call['JS']['Fullpath'];
                    else
                        $JSFilename = $Call['JS']['Pathname']
                                .$Call['JS']['Fullpath'];

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