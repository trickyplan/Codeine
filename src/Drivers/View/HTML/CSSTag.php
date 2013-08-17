<?php

    /* Codeine
     * @author BreathLess
     * @description Media includes support 
     * @package Codeine
     * @version 7.x
     */

    setFn('Hash', function ($Call)
    {
        $Hash = array ();

        foreach ($Call['IDs'] as $CSSFile)
        {
            list($Asset, $ID) = F::Run('View', 'Asset.Route', array ('Value' => $CSSFile));

            $Hash[] = $CSSFile.F::Run('IO', 'Execute', [
                                                               'Storage' => 'CSS',
                                                               'Scope'   => [$Asset, 'css'],
                                                               'Execute' => 'Version',
                                                               'Where'   =>
                                                               [
                                                                   'ID' => $ID
                                                               ]
                                                        ]);
        }

        return F::Run('Security.Hash', 'Get', ['Value' => implode('', $Hash)]);
    });

    setFn ('Process', function ($Call)
    {
        if (preg_match('/<place>CSS<\/place>/SsUu', $Call['Output']))
        {
            $Parsed = F::Run('Text.Regex', 'All',
                [
                    'Pattern' => $Call['CSS']['Pattern'],
                    'Value' => $Call['Output']
                ]);

            if ($Parsed)
            {
                $CSSHash = F::Run(null, 'Hash', ['IDs' => $Parsed[1]]);

                if ($Call['CSS']['Caching'] && F::Run('IO', 'Execute',
                        [
                            'Storage' => 'CSS Cache',
                            'Scope'   => [$Call['RHost'], 'css'],
                            'Execute' => 'Exist',
                            'Where'   =>
                            [
                                'ID' => $CSSHash
                            ]
                        ]))
                {
                    F::Log('Cache *hit*', LOG_GOOD);
                    // Ничего не делать
                }
                else
                {
                    F::Log('Cache *miss*', LOG_BAD);

                    $Call = F::Hook('beforeCSSRender', $Call);

                        foreach ($Parsed[1] as $CSSFile)
                        {
                            list($Asset, $ID) = F::Run('View', 'Asset.Route', array('Value' => $CSSFile));

                            $CSSSource = F::Run('IO', 'Read',
                                [
                                    'Storage' => 'CSS',
                                    'Scope'   => [$Asset, 'css'],
                                    'Where'   => $ID
                                ]);

                            if ($CSSSource)
                            {
                                $Call['CSS']['Styles'][] = $CSSSource[0];
                                F::Log('CSS loaded: '.$CSSFile, LOG_INFO);
                            }
                            else
                                F::Log('CSS cannot loaded: '.$CSSFile, LOG_INFO);
                        }

                        $Call['CSS']['Styles'] = implode (' ', $Call['CSS']['Styles']);

                    $Call = F::Hook('afterCSSRender', $Call);

                    F::Run ('IO', 'Write',
                            [
                                 'Storage' => 'CSS Cache',
                                 'Scope'   => [$Call['RHost'], 'css'],
                                 'Where'   => $CSSHash,
                                 'Data' => $Call['CSS']['Styles']
                            ]);
                }

                $Call['Output'] = str_replace($Parsed[0], '', $Call['Output']);

                if(!isset($Call['Proto']))
                    $Call['Proto'] ='';

                if (isset($Call['CSS Host']) && !empty($Call['CSS Host']))
                    $CSSOut = '<link href="'
                        .$Call['Proto']
                        .$Call['CSS Host']
                        .$Call['CSS']['Pathname']
                        .$CSSHash
                        .$Call['CSS']['Extension'].'" rel="stylesheet" type="'.$Call['CSS']['Type'].'"/>';
                else
                    $CSSOut = '<link href="'.$Call['CSS']['Pathname'].$CSSHash.$Call['CSS']['Extension'].'" rel="stylesheet" />';

                $Call['Output'] = str_replace('<place>CSS</place>', $CSSOut, $Call['Output']);
            }

        }

        return $Call;
    });