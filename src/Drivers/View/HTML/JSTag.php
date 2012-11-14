<?php

    /* Codeine
     * @author BreathLess
     * @description Media includes support 
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Hash', function ($Call)
    {
        $Hash = array ();

        foreach ($Call['IDs'] as $JSFile)
        {
            list($Asset, $ID) = F::Run('View', 'Asset.Route', array ('Value' => $JSFile));

            $Hash[] = $JSFile . F::Run('IO', 'Execute', array (
                                                               'Storage' => 'JS',
                                                               'Scope'   => $Asset.'/js',
                                                               'Execute' => 'Version',
                                                               'Where'   =>
                                                                   array(
                                                                       'ID' => $ID
                                                                   )
                                                         ));
        }

        return sha1(implode('', $Hash));
    });



    self::setFn('Process', function ($Call)
    {
        if (preg_match_all('/<jsrun>(.*)<\/jsrun>/SsUu', $Call['Output'], $Parsed))
        {
            $RunJS = implode(';', $Parsed[1]);
            $Call['Output'] = preg_replace('/<jsrun>(.*)<\/jsrun>/SsUu', '', $Call['Output']);
        }
        else
            $RunJS = '';

        if (preg_match_all('/<js>(.*)<\/js>/SsUu', $Call['Output'], $Parsed))
        {
            $Parsed[1] = array_unique($Parsed[1]);

            $JSHash = F::Run(null, 'Hash', array ('IDs' => $Parsed[1])).sha1($RunJS);

            if (!F::Run('IO', 'Execute', array ('Storage' => 'JS Cache', 'Execute'  => 'Exist', 'Where' => array('ID' => $JSHash))))
            {
                $JS = array ();

                $Parsed[1] = array_unique($Parsed[1]);
                foreach ($Parsed[1] as $JSFile)
                {
                    list($Asset, $ID) = F::Run('View', 'Asset.Route', array ('Value' => $JSFile));

                    if(null == ($JS[] = F::Run('IO', 'Read', array (
                                                        'Storage' => 'JS',
                                                        'Scope'   => $Asset . '/js',
                                                        'Where'   => $ID
                                                  ))[0]))
                        F::Log('JS cannot loaded: '.$JSFile, LOG_WARNING);
                    else
                        F::Log('JS loaded: '.$JSFile);
                }

                $JS = implode('', $JS).$RunJS;

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

                $Call['Output'] = str_replace($Parsed[0], '', $Call['Output']);

            if (isset($Call['Async']))
                $Call['Output'] = str_replace('<place>JS</place>',
                '<script type="text/javascript"> var script = document.createElement(\'script\'); script.src = \''.$Call['JS Host'].'/cache/js/'.$JSHash.'.js\';                    document.getElementsByTagName(\'head\')[0].appendChild(script);</script>', $Call['Output']);

            else
                $Call['Output'] = str_replace('<place>JS</place>',
                    '<script src="'.$Call['JS Host'].'/cache/js/' . $JSHash . '.js" type="text/javascript"></script>', $Call['Output']);
        }

        return $Call;
    });