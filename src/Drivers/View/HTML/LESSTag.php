<?php

    /* Codeine
     * @author BreathLess
     * @description LESS tags support
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn ('Process', function ($Call)
    {
        if (preg_match_all ('@<less>(.*)<\/less>@SsUu', $Call['Output'], $Parsed))
        {
            {
                foreach ($Parsed[0] as $IX => $Match)
                    list($Asset, $ID) = F::Run('View', 'Asset.Route', array ('Value' => $Parsed[1][$IX]));

                $LESSFile = F::findFile('Assets/' . $Asset . '/less/' . $ID . '.less');

                if (filemtime($LESSFile) >
                    F::Run('IO', 'Execute', array (
                        'Storage' => 'CSS',
                        'Scope'   => $Asset . '/css',
                        'Execute' => 'Version',
                        'Where'   => $ID
                    )))
                {
                    F::Run('IO', 'Write',
                        array (
                              'Storage' => 'CSS',
                              'Where'   => $Parsed[1][$IX],
                              'Scope'   => $Asset . '/css',
                              'Data'    => F::Run('View.LESS.CLI', 'Compile',
                                   array (
                                         'Value' => $LESSFile,
                                         'CSS' => Root.'/Assets/'.$Asset.'/css/'.$ID.'.css') // FIXME
                               )
                        ));
                };

                $Call['Output'] = str_replace($Match, '<css>'.$Asset.':'.$ID.'</css>', $Call['Output']);
            }
        }

        return $Call;
    });