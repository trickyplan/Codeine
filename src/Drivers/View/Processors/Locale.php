<?php

    /* Codeine
     * @author BreathLess
     * @description: 
     * @package Codeine
     * @version 7.0
     */

    self::setFn('Process', function ($Call)
    {
        if (preg_match_all('@<l>(.*)<\/l>@SsUu', $Call['Output'], $Pockets))
        {
            // FIXME Codeinize
            $Locales = F::Run('Engine.IO', 'Read',
                array(
                     'Storage' => 'Language',
                     'Where' => array(
                         'ID' => F::Run('System.Interface.Web', 'DetectUALanguage'))
                ));

            foreach ($Pockets[1] as $IX => $Match)
            {
                $Slices = explode('.', $Match);
                $szSlices = sizeof($Slices);

                $TrueMatch = false;

                for($ic = $szSlices; $ic > 0; --$ic)
                    if (isset($Locales[$Match = implode('.', array_slice($Slices, 0, $ic))]))
                        $TrueMatch = $Match;

                if ($TrueMatch)
                    $Call['Output'] = str_replace ($Pockets[0][$IX], $Locales[$Match], $Call['Output']);
                else
                    $Call['Output'] = str_replace($Pockets[0][$IX], '<span class="nl">'.$Match.'</span>', $Call['Output']);
            }
        }
        return $Call;
    });
