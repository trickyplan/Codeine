<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Media includes support 
     * @package Codeine
     * @version 8.x
     */

    setFn ('Do', function ($Call)
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
                foreach ($Call['CSS']['Input'] as $Call['CSS Name'])
                {
                    $Call = F::Hook('beforeCSSLoad', $Call);

                        list($Asset, $ID) = F::Run('View', 'Asset.Route',
                            [
                                'Value' => $Call['CSS Name'],
                                'Scope' => 'css'
                            ]);

                        /*if (isset($Call['CSS']['Styles'][$Call['CSS Name']]))
                            ;
                        else*/
                            $Call['CSS']['Styles'][$Call['CSS Name']] = F::Run('IO', 'Read',
                                [
                                    'Storage' => 'CSS',
                                    'Scope'   => $Asset,
                                    'Where'   => $ID
                                ])[0];

                        if ($Call['CSS']['Styles'][$Call['CSS Name']])
                            F::Log('CSS loaded: '.$Call['CSS Name'], LOG_DEBUG);
                        else
                            F::Log('CSS cannot loaded: '.$Call['CSS Name'], LOG_ERR);

                    $Call = F::Hook('afterCSSLoad', $Call);
                }

                if (!empty($CSSInline))
                    $Call['CSS']['Styles'][] = $CSSInline;

                $Call = F::Hook('afterCSSInput', $Call);

                $Call = F::Hook('beforeCSSOutput', $Call);

                // CSS Output

                foreach ($Call['CSS']['Styles'] as $Call['CSS']['Fullpath'] => $Call['CSS']['Source'])
                {
                    $Call['CSS']['Fullpath'] = strtr($Call['CSS']['Fullpath'], ":", '_').'_'.sha1($Call['CSS']['Source']).$Call['CSS']['Extension'];

                    $Write = true;

                    if ($Call['CSS']['Caching'])
                    {
                        if (F::Run('IO', 'Execute', $Call,
                        [
                            'Storage' => 'CSS Cache',
                            'Execute' => 'Exist',
                            'Where'   =>
                            [
                                'ID' => $Call['CSS']['Fullpath']
                            ]
                        ]))
                        {
                            F::Log('Cache *hit* '.$Call['CSS']['Fullpath'], LOG_DEBUG);
                            $Write = false;
                        }
                        else
                        {
                            F::Log('Cache *miss* '.$Call['CSS']['Fullpath'], LOG_NOTICE);
                        }
                    }

                    if ($Write)
                    {
                        $Call = F::Hook('beforeCSSWrite', $Call);

                            F::Run ('IO', 'Write', $Call,
                            [
                                 'Storage' => 'CSS Cache',
                                 'Where'   => $Call['CSS']['Fullpath'],
                                 'Data' => $Call['CSS']['Source']
                            ]);

                        $Call = F::Hook('afterCSSWrite', $Call);
                    }

                    $SRC = F::Run('IO', 'Execute', $Call,
                    [
                        'Storage' => 'CSS Cache',
                        'Execute' => 'Filename',
                        'Where'   => $Call['CSS']['Fullpath']
                    ]);

                    if (isset($Call['CSS']['Host']) && !empty($Call['CSS']['Host']))
                        $Call['CSS']['Links'][] = '<link href="'.$Call['HTTP']['Proto'].$Call['CSS']['Host'].$SRC.'" rel="stylesheet" type="'.$Call['CSS']['Type'].'"/>';
                    else
                        $Call['CSS']['Links'][] = '<link href="'.$SRC.'" rel="stylesheet" type="'.$Call['CSS']['Type'].'" />';

                }

                $Call = F::Hook('afterCSSOutput', $Call);


                $Call['Output'] = str_replace('<place>CSS</place>', implode(PHP_EOL, $Call['CSS']['Links']), $Call['Output']);
            }

            $Call['Output'] = str_replace($Parsed[0], '', $Call['Output']);

            unset ($Call['CSS']);
        }

        return $Call;
    });