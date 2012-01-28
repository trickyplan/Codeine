<?php

    /* Codeine
     * @author BreathLess
     * @description Media includes support 
     * @package Codeine
     * @version 7.0
     */

    self::setFn ('Process', function ($Call)
    {
        if (preg_match_all ('@<js>(.*)<\/js>@SsUu', $Call['Output'], $Parsed))
        {
            $JSHash = F::Run('View.Processors.HTML.JS.Hash', 'Get', array('IDs' => $Parsed[1]));

            if (!F::Run('Engine.IO', 'Execute', array('Storage' => 'JS Cache', 'Execute' => 'Exist',
                                                     'Where' => array('ID' => $JSHash))))
            {
                $JS = array();
                foreach ($Parsed[1] as $JSFile)
                {
                    $JS[] = F::Run ('Engine.IO', 'Read', array(
                                                      'Storage' => 'JS',
                                                      'Where'   =>
                                                      array(
                                                          'ID' => $JSFile
                                                      )
                                                 ));
                }

                // TODO Minimize
                F::Run ('Engine.IO', 'Write',
                        array(
                             'Storage' => 'JS Cache',
                             'Where'   =>
                                 array(
                                     'ID' => $JSHash
                                 ),
                             'Data' => implode ('', $JS)
                        ));

            }

            foreach ($Parsed[0] as $cParsed)
                $Call['Output'] = str_replace($cParsed,'', $Call['Output']);

            // TODO Codeinize
            $Call['Output'] = str_replace ('<place>JS</place>', '<script type="text/javascript" src="/js/' . $JSHash . '.js"></script>', $Call['Output']);
        }

        return $Call;
    });