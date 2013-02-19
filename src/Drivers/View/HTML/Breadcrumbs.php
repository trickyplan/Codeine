<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Scan', function ($Call)
    {
        $Breadcrumbs = '';

        if (preg_match_all('@<breadcrumb (.+)>(.+)<\/breadcrumb>@SsUu', $Call['Output'], $Pockets))
        {
            foreach ($Pockets[0] as $IX => $Match)
            {
                $Match = simplexml_load_string('<breadcrumb '.$Pockets[1][$IX].'></breadcrumb>');

                $URL = (string) $Match->attributes()->href;
                $Call['Breadcrumbs'][strlen($URL)] = array('URL' => $URL, 'Title' => $Pockets[2][$IX]);
                $Call['Output'] = str_replace($Pockets[0][$IX], '', $Call['Output']);
            }

            $Last = array_pop($Call['Breadcrumbs']);

            foreach ($Call['Breadcrumbs'] as $Breadcrumb)
                $Breadcrumbs.= F::Run('View', 'LoadParsed', array(
                    'Scope' => 'Default',
                    'ID' => 'UI/Breadcrumb/Active',
                    'Data' => $Breadcrumb
                ));

            $Breadcrumbs.= F::Run('View', 'LoadParsed', array(
                    'Scope' => 'Default',
                    'ID' => 'UI/Breadcrumb/Passive',
                    'Data' => $Last
                ));

        }

        if (isset($Call['Breadcrumbs']) && count($Call['Breadcrumbs']) > 0)
        {
            $Breadcrumbs = F::Run('View', 'LoadParsed', array(
                    'Scope' => 'Default',
                    'ID' => 'UI/Breadcrumb',
                    'Data' => ['Breadcrumbs' => $Breadcrumbs]
                ));

            $Call['Output'] = str_replace('<breadcrumbs/>', $Breadcrumbs, $Call['Output']);
        }
        else
            $Call['Output'] = str_replace('<breadcrumbs/>', '', $Call['Output']);

        return $Call;
    });