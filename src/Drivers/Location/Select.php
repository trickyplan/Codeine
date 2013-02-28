<?php

    /* Sphinx
     * @author BreathLess
     * @description  
     * @package Sphinx
     * @version 7.2
     */

    setFn('Widget', function ($Call)
    {
        $Locations = F::Run('Entity', 'Read', ['Entity' => 'Location']);

        $Here = F::Run('Entity', 'Read', ['Entity' => 'Location', 'Where' => $Call['Location']])[0];

        foreach ($Locations as $Location)
        {
            if (null == $Here['Slug'])
                $Location['URL'] = '/'.$Location['Slug'].$Call['URL'];
            else
            {
                if (preg_match('@^/'.$Here['Slug'].'@Ssuu', $Call['URL']))
                    $Location['URL'] = str_replace($Here['Slug'], $Location['Slug'], $Call['URL']);
                else
                    $Location['URL'] = '/'.$Location['Slug'];
            }

            $Call['Output']['Content'][] =
                array(
                    'Type' => 'Template',
                    'Scope' => 'Location',
                    'ID' => 'Show/Widget',
                    'Data' => $Location
                );
        }

        return $Call;
    });