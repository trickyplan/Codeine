<?php

    /* Codeine
     * @author BreathLess
     * @description Media includes support 
     * @package Codeine
     * @version 7.2
     */

    self::setFn ('Process', function ($Call)
    {
        $CSSHash = '';

        if (preg_match_all ('@<css>(.*)<\/css>@SsUu', $Call['Output'], $Parsed))
        {
            $CSSHash = F::Run('View.Processors.HTML.CSS.Hash', 'Get', array('IDs' => $Parsed[1]));

            //if (F::Run('IO', 'Execute', array('Storage' => 'Tag Cache', 'Execute' => 'Exist',
            //                                         'Where' => array('ID' => $CSSHash))))
            {
                $CSS = array();
                foreach ($Parsed[1] as $CSSFile)
                {
                    $CSS[] = F::Run ('IO', 'Read', array(
                                                      'Storage' => 'CSS',
                                                      'Where'   =>
                                                      array(
                                                          'ID' => $CSSFile
                                                      )
                                                 ));
                }

                $CSS = implode ('', $CSS);

                if (isset($Call['CSS.Postprocessors']) && $Call['CSS.Postprocessors'])
                    foreach($Call['CSS.Postprocessors'] as $Processor)
                        $CSS = F::Run($Processor['Service'], $Processor['Method'], array('Value' => $CSS));

                F::Run ('IO', 'Write',
                        array(
                             'Storage' => 'CSS Cache',
                             'Where'   =>
                                 array(
                                     'ID' => $CSSHash
                                 ),
                             'Data' => $CSS
                        ));

            }

            foreach ($Parsed[0] as $cParsed)
                $Call['Output'] = str_replace($cParsed,'', $Call['Output']);
        }

        // TODO Codeinize
        $Call['Output'] = str_replace('<place>CSS</place>', '<link href="/css/'.$CSSHash.'.css" rel="stylesheet" >', $Call['Output']);

        return $Call;
    });