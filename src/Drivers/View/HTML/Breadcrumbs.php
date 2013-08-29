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

                if ($Match)
                {
                    $URL = (string) $Match->attributes()->href;

                    $Call['Breadcrumbs'][] = ['URL' => $URL, 'Title' => $Pockets[2][$IX]];
                    $Call['Output'] = str_replace($Pockets[0][$IX], '', $Call['Output']);
                }
            }

            $Last = array_pop($Call['Breadcrumbs']);

            foreach ($Call['Breadcrumbs'] as $Breadcrumb)
            {
                $Breadcrumbs.= F::Run('View', 'Load',
                    [
                        'Scope' => $Call['Widget Set'].'/Widgets',
                        'ID' => 'Breadcrumb/Active',
                        'Data' => $Breadcrumb
                    ]);
            }

            $Breadcrumbs.= F::Run('View', 'Load',
                    [
                        'Scope' => $Call['Widget Set'].'/Widgets',
                        'ID' => 'Breadcrumb/Passive',
                        'Data' => $Last
                    ]);

        }

        if (isset($Call['Breadcrumbs']) && count($Call['Breadcrumbs']) > 0)
        {
            $Breadcrumbs = F::Run('View', 'Load', array(
                    'Scope' => $Call['Widget Set'].'/Widgets',
                    'ID' => 'Breadcrumb',
                    'Data' => ['Breadcrumbs' => $Breadcrumbs]
                ));

            $Call['Output'] = str_replace('<breadcrumbs/>', $Breadcrumbs, $Call['Output']);
        }
        else
            $Call['Output'] = str_replace('<breadcrumbs/>', '', $Call['Output']);

        return $Call;
    });