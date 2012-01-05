<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 6.0
     */

    self::setFn('Process', function ($Call)
    {
        if (preg_match_all('@<l>(.*)<\/l>@SsUu', $Call['Output'], $Pockets))
        {
            // FIXME Codeinize
            $Locales = F::Run('Engine.IO', 'Read',
                array(
                     'Storage' => 'Language',
                     'Where' => array('ID' => 'Russian')
                ));

            foreach ($Pockets[1] as $IX => $Match)
            {
                if (isset($Locales[$Match]))
                    $Call['Output'] = str_replace($Pockets[0][$IX], $Locales[$Match], $Call['Output']);
                else
                    $Call['Output'] = str_replace($Pockets[0][$IX], '<span class="nl">'.$Match.'</span>', $Call['Output']);
            }
        }
        return $Call;
    });
