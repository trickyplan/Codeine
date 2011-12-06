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
            return F::Run(array(
                              '_N' => 'Engine.Data',
                              '_F' => 'Load',
                              'Storage' => 'Layout',
                              'Scope' => 'Layout',
                              'ID' => array($Call['ID'].$Call['Context'], $Call['ID'].'.html')
                          ));
    });