<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Scan', function ($Call)
    {
        $Breadcrumbs = '';

        if (preg_match_all('@<breadcrumb (.+)>(.*)<\/breadcrumb>@SsUu', $Call['Output'], $Pockets))
        {
            foreach ($Pockets[0] as $IX => $Match)
            {
                $Match = simplexml_load_string('<breadcrumb '.$Pockets[1][$IX].'></breadcrumb>');

                if ($Match)
                {
                    $URL = (string) $Match->attributes()->href;

                    $Call['Breadcrumbs'][] = ['URL' => $URL, 'Title' => strip_tags($Pockets[2][$IX], '<l>')];
                    $Call['Output'] = str_replace($Pockets[0][$IX], '', $Call['Output']);
                }
            }

            $First = array_shift($Call['Breadcrumbs']);

            if (F::Dot($Call, 'View.HTML.Breadcrumbs.Max'))
                $Call['Breadcrumbs'] = array_slice($Call['Breadcrumbs'], -F::Dot($Call, 'View.HTML.Breadcrumbs.Max'));

            $Last = array_pop($Call['Breadcrumbs']);

            $Breadcrumbs = F::Run('View', 'Load',
                [
                    'Scope' => $Call['View']['HTML']['Widget Set'].'/Widgets',
                    'ID' => 'Breadcrumb/Active',
                    'Data' => $First
                ]);

            foreach ($Call['Breadcrumbs'] as $Breadcrumb)
            {
                $Breadcrumbs.= F::Run('View', 'Load',
                    [
                        'Scope' => $Call['View']['HTML']['Widget Set'].'/Widgets',
                        'ID' => 'Breadcrumb/Active',
                        'Data' => $Breadcrumb
                    ]);
            }

            $Breadcrumbs.= F::Run('View', 'Load',
                    [
                        'Scope' => $Call['View']['HTML']['Widget Set'].'/Widgets',
                        'ID' => 'Breadcrumb/Passive',
                        'Data' => $Last
                    ]);

        }

        if (isset($Call['Breadcrumbs']) && count($Call['Breadcrumbs']) > 0)
        {
            $Breadcrumbs = F::Run('View', 'Load',
                [
                    'Scope' => $Call['View']['HTML']['Widget Set'].'/Widgets',
                    'ID' => 'Breadcrumb',
                    'Data' => ['Breadcrumbs' => $Breadcrumbs]
                ]);

            $Call['Output'] = str_replace('<breadcrumbs/>', $Breadcrumbs, $Call['Output']);
        }
        else
            $Call['Output'] = str_replace('<breadcrumbs/>', '', $Call['Output']);

        return $Call;
    });