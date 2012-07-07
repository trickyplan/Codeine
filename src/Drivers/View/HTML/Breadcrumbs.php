<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn('Scan', function ($Call)
    {
        $Breadcrumbs = '';

        if (preg_match_all('@<breadcrumb (.*)>(.*)<\/breadcrumb>@SsUu', $Call['Output'], $Pockets))
        {
            foreach ($Pockets[0] as $IX => $Match)
            {
                $Match = simplexml_load_string($Match);

                $URL = (string) $Match->attributes()->href;
                $Call['Breadcrumbs'][strlen($URL)] = array('URL' => $URL, 'Title' => $Pockets[2][$IX]);
                $Call['Output'] = str_replace($Pockets[0][$IX], '', $Call['Output']);
            }

            ksort($Call['Breadcrumbs']);


            $Last = array_pop($Call['Breadcrumbs']);

            foreach ($Call['Breadcrumbs'] as $Breadcrumb)
                $Breadcrumbs.= F::Run('View', 'LoadParsed', array(
                    'Scope' => 'Default',
                    'ID' => 'UI/HTML/Breadcrumb/Active',
                    'Data' => $Breadcrumb
                ));

            $Breadcrumbs.= F::Run('View', 'LoadParsed', array(
                    'Scope' => 'Default',
                    'ID' => 'UI/HTML/Breadcrumb/Passive',
                    'Data' => $Last
                ));

        }



        $Call['Output'] = str_replace('<breadcrumbs/>', $Breadcrumbs, $Call['Output']);

        return $Call;
    });