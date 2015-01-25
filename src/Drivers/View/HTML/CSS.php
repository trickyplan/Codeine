<?php

    /* Codeine
     * @author BreathLess
     * @description Media includes support 
     * @package Codeine
     * @version 8.x
     */

    setFn ('Process', function ($Call)
    {
        $Cache = F::Run('IO', 'Open', ['Storage' => 'CSS Cache']);

        if (preg_match('/<place>CSS<\/place>/SsUu', $Call['Output']))
        {

            $Parsed = F::Run('Text.Regex', 'All',
                [
                    'Pattern' => $Call['CSS']['Inline Pattern'],
                    'Value' => $Call['Output']
                ]);

            if ($Parsed)
            {
                $CSSInline = implode(';', $Parsed[1]);
                $Call['Output'] = str_replace($Parsed[0], '', $Call['Output']);
            }
            else
                $CSSInline = '';

            $Parsed = F::Run('Text.Regex', 'All',
                [
                    'Pattern' => $Call['CSS']['Pattern'],
                    'Value' => $Call['Output']
                ]);


            if ($Parsed)
            {

                $Call['CSS']['Input'] = $Parsed[1];

                $Call = F::Hook('beforeCSSInput', $Call);

                // CSS Input
                foreach ($Call['CSS']['Input'] as $Call['CSS Filename'])
                {
                    $Call = F::Hook('beforeCSSLoad', $Call);

                        list($Asset, $ID) = F::Run('View', 'Asset.Route', ['Value' => $Call['CSS Filename']]);

                        $Call['CSS']['Styles'][$Call['CSS Filename']] = F::Run('IO', 'Read',
                            [
                                'Storage' => 'CSS',
                                'Scope'   => [$Asset, 'css'],
                                'Where'   => $ID
                            ])[0];

                        if ($Call['CSS']['Styles'][$Call['CSS Filename']])
                            F::Log('CSS loaded: '.$Call['CSS Filename'], LOG_DEBUG);
                        else
                            F::Log('CSS cannot loaded: '.$Call['CSS Filename'], LOG_ERR);

                    $Call = F::Hook('afterCSSLoad', $Call);
                }

                if (!empty($CSSInline))
                    $Call['CSS']['Styles'][] = $CSSInline;

                $Call = F::Hook('afterCSSInput', $Call);

                $Call = F::Hook('beforeCSSOutput', $Call);

                // CSS Output

                if (isset($Call['CSS']['Host']) && !empty($Call['CSS']['Host']))
                    $Host = $Call['CSS']['Host'];
                else
                    $Host = $Call['HTTP']['Host'];

                foreach ($Call['CSS']['Styles'] as $Call['CSS']['Fullpath'] => $CSSSource)
                {
                    $Call['CSS']['Fullpath'] = sha1($CSSSource).'_'.strtr($Call['CSS']['Fullpath'], ":", '_') .$Call['CSS']['Extension'];
                    $Call['CSS']['Cached Filename'] = $Cache['Directory'].DS.$Call['HTTP']['Host'].DS.'css'.DS.$Call['CSS']['Scope'].DS.$Call['CSS']['Fullpath'];

                    $Write = true;

                    if ($Call['CSS']['Caching'])
                    {
                        if (F::Run('IO', 'Execute',
                        [
                            'Storage' => 'CSS Cache',
                            'Scope'   => [$Host, 'css'],
                            'Execute' => 'Exist',
                            'Where'   =>
                            [
                                'ID' => $Call['CSS']['Fullpath']
                            ]
                        ]))
                        {
                            F::Log('Cache *hit* '.$Call['CSS']['Fullpath'], LOG_GOOD);
                            $Write = false;
                        }
                        else
                        {
                            F::Log('Cache *miss*', LOG_BAD);
                        }
                    }

                    if ($Write)
                    {
                        $Call = F::Hook('beforeCSSWrite', $Call);

                            F::Run ('IO', 'Write',
                            [
                                 'Storage' => 'CSS Cache',
                                 'Scope'   => [$Host, 'css'],
                                 'Where'   => $Call['CSS']['Fullpath'],
                                 'Data' => $CSSSource
                            ]);

                        $Call = F::Hook('afterCSSWrite', $Call);
                    }


                    if (isset($Call['CSS']['Host']) && !empty($Call['CSS']['Host']))
                        $Call['CSS']['Links'][] = '<link href="'
                            .$Call['HTTP']['Proto']
                            .$Call['CSS']['Host']
                            .$Call['CSS']['Pathname']
                            .$Call['CSS']['Fullpath'].'" rel="stylesheet" type="'.$Call['CSS']['Type'].'"/>';
                    else
                        $Call['CSS']['Links'][]
                            = '<link href="'.$Call['CSS']['Pathname'].$Call['CSS']['Fullpath'].'" rel="stylesheet" type="'.$Call['CSS']['Type'].'" />';

                }

                $Call = F::Hook('afterCSSOutput', $Call);


                $Call['Output'] = str_replace('<place>CSS</place>', implode(PHP_EOL, $Call['CSS']['Links']), $Call['Output']);
            }

            $Call['Output'] = str_replace($Parsed[0], '', $Call['Output']);
        }

        unset ($Call['CSS']);

        return $Call;
    });