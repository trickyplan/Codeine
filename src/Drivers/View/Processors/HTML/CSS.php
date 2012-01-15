<?php

    /* Codeine
     * @author BreathLess
     * @description Media includes support 
     * @package Codeine
     * @version 6.0
     */

    self::setFn ('Process', function ($Call)
    {
        if (preg_match_all ('@<css>(.*)<\/css>@SsUu', $Call['Output'], $Parsed))
        {
            $CSSHash = F::Run('View.Processors.HTML.CSS.Hash', 'Get', array('IDs' => $Parsed[1]));

            //if (F::Run('Engine.IO', 'Execute', array('Storage' => 'CSS Cache', 'Execute' => 'Exist',
            //                                         'Where' => array('ID' => $CSSHash))))
            {
                $CSS = array();
                foreach ($Parsed[1] as $CSSFile)
                {
                    $CSS[] = F::Run ('Engine.IO', 'Read', array(
                                                      'Storage' => 'CSS',
                                                      'Where'   =>
                                                      array(
                                                          'ID' => $CSSFile
                                                      )
                                                 ));
                }

                $CSS = implode ('', $CSS);

                if (isset($Call['Minify']) && $Call['Minify'])
                    $CSS = F::Run('View.Processors.CSS.Minify', 'Process', array('Value' => $CSS));

                F::Run ('Engine.IO', 'Write',
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