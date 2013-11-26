<?php

    /* Codeine
     * @author BreathLess
     * @description Media includes support 
     * @package Codeine
     * @version 7.x
     */

    setFn ('Process', function ($Call)
    {
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
                foreach ($Call['CSS']['Input'] as $CSS)
                {
                    list($Asset, $ID) = F::Run('View', 'Asset.Route', ['Value' => $CSS]);

                    $Call['CSS']['Styles'][$CSS] = F::Run('IO', 'Read',
                        [
                            'Storage' => 'CSS',
                            'Scope'   => [$Asset, 'css'],
                            'Where'   => $ID
                        ])[0];

                    if ($Call['CSS']['Styles'][$CSS])
                        F::Log('CSS loaded: '.$CSS, LOG_DEBUG);
                    else
                        F::Log('CSS cannot loaded: '.$CSS, LOG_ERR);
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

                foreach ($Call['CSS']['Styles'] as $CSS => $CSSSource)
                {
                    $CSS = sha1($CSSSource).'_'.$CSS;

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
                                'ID' => $CSS
                            ]
                        ]))
                        {
                            F::Log('Cache *hit* '.$CSS, LOG_GOOD);
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
                                 'Where'   => $CSS,
                                 'Data' => $CSSSource
                            ]);

                        $Call = F::Hook('afterCSSWrite', $Call);
                    }


                    if (isset($Call['CSS']['Host']) && !empty($Call['CSS']['Host']))
                        $Call['CSS']['Links'][] = '<link href="'
                            .$Call['CSS']['Proto']
                            .$Call['CSS']['Host']
                            .$Call['CSS']['Pathname']
                            .$CSS
                            .$Call['CSS']['Extension'].'" rel="stylesheet" type="'.$Call['CSS']['Type'].'"/>';
                    else
                        $Call['CSS']['Links'][]
                            = '<link href="'.$Call['CSS']['Pathname'].$CSS.$Call['CSS']['Extension'].'" rel="stylesheet" type="'.$Call['CSS']['Type'].'" />';

                }

                $Call = F::Hook('afterCSSOutput', $Call);


                $Call['Output'] = str_replace('<place>CSS</place>', implode(PHP_EOL, $Call['CSS']['Links']), $Call['Output']);
            }

            $Call['Output'] = str_replace($Parsed[0], '', $Call['Output']);
        }

        unset ($Call['CSS']);

        return $Call;
    });