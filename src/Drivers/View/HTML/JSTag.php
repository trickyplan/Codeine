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

        foreach ($Call['IDs'] as $JSFile)
        {
            list($Asset, $ID) = F::Run('View', 'Asset.Route', array ('Value' => $JSFile));

            $Hash[] = $JSFile . F::Run('IO', 'Execute', [
                                                           'Storage' => 'JS',
                                                           'Scope'   => [$Asset, 'js'],
                                                           'Execute' => 'Version',
                                                           'Where'   =>
                                                           [
                                                               'ID' => $ID
                                                           ]
                                                         ]);
        }

        return F::Run('Security.Hash', 'Get', ['Value' => implode('', $Hash).$Call['Runs']]);
    });

    setFn('Process', function ($Call)
    {
        if (preg_match('/<place>JS<\/place>/SsUu', $Call['Output']))
        {
            if (preg_match_all('/<jsrun>(.*)<\/jsrun>/SsUu', $Call['Output'], $Parsed))
            {
                $RunJS = implode(';', $Parsed[1]);
                $Call['Output'] = str_replace($Parsed[0], '', $Call['Output']);
            }
            else
                $RunJS = '';

            if ($Parsed = F::Run('Text.Regex', 'All', ['Pattern' => '<js>(.+?)<\/js>', 'Value' => $Call['Output']]))
            {
                $Parsed[1] = array_unique($Parsed[1]);

                $JSHash = F::Run(null, 'Hash', ['IDs' => $Parsed[1], 'Runs' => $RunJS]);

                if ($Call['JS']['Caching'] && F::Run('IO', 'Execute',
                    [
                        'Storage' => 'JS Cache',
                        'Scope'   => [$Call['RHost'], 'js'],
                        'Execute'  => 'Exist',
                        'Where' => ['ID' => $JSHash]
                    ]))
                {
                    F::Log('Cache *hit*', LOG_GOOD);
                }
                else
                {
                    F::Log('Cache *miss*', LOG_BAD);
                    $Parsed[1] = array_unique($Parsed[1]);

                    foreach ($Parsed[1] as $JSFile)
                    {
                        list($Asset, $ID) = F::Run('View', 'Asset.Route', array ('Value' => $JSFile));

                        if (null == ($Call['JS']['Scripts'][] = F::Run('IO', 'Read', [
                                                            'Storage' => 'JS',
                                                            'Scope'   => [$Asset, 'js'],
                                                            'Where'   => $ID
                                                      ])[0]))

                            F::Log('JS cannot loaded: '.$JSFile, LOG_WARNING);
                        else
                            F::Log('JS loaded: '.$JSFile);
                    }

                    $Call['JS']['Scripts'] = implode(';', $Call['JS']['Scripts']).$RunJS;

                    $Call = F::Hook('afterJSRender', $Call);

                    F::Run('IO', 'Write',
                        [
                              'Storage' => 'JS Cache',
                              'Scope'   => [$Call['RHost'], 'js'],
                              'Where'   => $JSHash,
                              'Data'    => $Call['JS']['Scripts']
                        ]);

                }

                $Call['Output'] = str_replace($Parsed[0], '', $Call['Output']);

                if(!isset($Call['Proto']))
                    $Call['Proto'] ='';

                if (isset($Call['JS Host']) && !empty($Call['JS Host']))
                    $Source = $Call['Proto'].$Call['JS Host'].$Call['JS']['Pathname'].$JSHash.$Call['JS']['Extension'];
                else
                    $Source = $Call['JS']['Pathname'].$JSHash.$Call['JS']['Extension'];

                if (isset($Call['Async']))
                    $JSOut = '<script type="'.$Call['JS']['Type'].'">
                        var script = document.createElement(\'script\');
                        script.src = \''.$Source.'\';
                        document.getElementsByTagName(\'head\')[0].appendChild(script);
                    </script>';
                else
                    $JSOut = '<script src="'.$Source.'" type="'.$Call['JS']['Type'].'"></script>';

                $Call['Output'] = str_replace('<place>JS</place>', $JSOut, $Call['Output']);

            }

        }

        return $Call;
    });