<?php

    /* Codeine
     * @author BreathLess
     * @description Media includes support 
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Hash', function ($Call)
    {
        $Hash = array ();

        foreach ($Call['IDs'] as $JSFile)
        {
            F::Log($JSFile);
            list($Asset, $ID) = F::Run('View', 'Asset.Route', array ('Value' => $JSFile));

            $Hash[] = $JSFile . F::Run('IO', 'Execute', array (
                                                               'Storage' => 'JS',
                                                               'Scope'   => $Asset.'/js',
                                                               'Execute' => 'Version',
                                                               'Where'   => $ID
                                                         ));
        }

        return sha1(implode('', $Hash));
    });



    self::setFn('Process', function ($Call)
    {
        if (preg_match_all('/<js>(.*)<\/js>/SsUu', $Call['Output'], $Parsed))
        {
            $JSHash = F::Run(null, 'Hash', array ('IDs' => $Parsed[1]));

            if (!F::Run('IO', 'Execute', array ('Storage' => 'JS Cache', 'Execute'  => 'Exist', 'Where' => $JSHash)))
            {
                $JS = array ();

                $Parsed[1] = array_unique($Parsed[1]);
                foreach ($Parsed[1] as $JSFile)
                {
                    F::Log($JSFile);
                    list($Asset, $ID) = F::Run('View', 'Asset.Route', array ('Value' => $JSFile));

                    if(null == ($JS[] = F::Run('IO', 'Read', array (
                                                        'Storage' => 'JS',
                                                        'Scope'   => $Asset . '/js',
                                                        'Where'   => $ID
                                                  ))))
                        trigger_error('No JS: '.$JSFile);
                }

                $JS = implode('', $JS);

                if (isset($Call['JS.Postprocessors']) && $Call['JS.Postprocessors'])
                    foreach ($Call['JS.Postprocessors'] as $Processor)
                        $JS = F::Run($Processor['Service'], $Processor['Method'], array ('Value' => $JS));

                F::Run('IO', 'Write',
                    array (
                          'Storage' => 'JS Cache',
                          'Where'   => $JSHash,
                          'Data'    => $JS
                    ));

            }

            foreach (
                $Parsed[0] as $cParsed
            )
                $Call['Output'] = str_replace($cParsed, '', $Call['Output']);

            $Call['Output'] = str_replace('<place>JS</place>', '<script src="/js/' . $JSHash . '.js" type="text/javascript"></script>', $Call['Output']);
        }

        return $Call;
    });