<?php

   /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::setFn ('Parse', function ($Call)
    {
        if (preg_match_all('@<k>(.*)</k>@SsUu', $Call['Value'], $Pockets))
        {
            foreach ($Pockets[1] as $IX => $Match)
                if (isset($Call['Data'][$Match]))
                    $Call['Value'] = str_replace($Pockets[0][$IX], $Call['Data'][$Match], $Call['Value']);
                else
                    $Call['Value'] = str_replace($Pockets[0][$IX], '', $Call['Value']);
        }

        return $Call['Value'];
     });

    self::setFn ('Load', function ($Call)
        {
            return $Call['Layout'] = F::Run ('Engine.IO', 'Read', $Call,
                     array(
                           'Storage' => 'Layout',
                           'Where'   => array('ID' => $Call['ID'].'.html')
                      ));
    });