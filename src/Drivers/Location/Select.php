<?php

    /* Sphinx
     * @author BreathLess
     * @description  
     * @package Sphinx
     * @version 7.2
     */

    setFn('Widget', function ($Call)
    {
        $Locations = F::Run('Entity', 'Read', ['Entity' => 'Location', 'NoPage' => true, 'Sort' => ['Priority' => false, 'Title' => true]]);

        $Here = F::Run('Entity', 'Read', ['Entity' => 'Location', 'Where' => $Call['Location'], 'One' => true]);

        if ($Call['URL'] == '/')
            $Call['URL'] = '';

        foreach ($Locations as $Location)
        {
            if (null === $Here['Slug'])
                $Location['URL'] = '/'.$Location['Slug'];
            else
            {
                if (preg_match('@^/'.$Here['Slug'].'@Ssuu', $Call['URL']))
                    $Location['URL'] = str_replace($Here['Slug'], $Location['Slug'], $Call['URL']);
                else
                    $Location['URL'] = '/'.$Location['Slug'];
            }

            $Call['Output']['Content'][] =
                [
                    'Type' => 'Template',
                    'Scope' => 'Location',
                    'ID' => 'Show/Widget',
                    'Data' => $Location
                ];
        }

        return $Call;
    });

    setFn('Select', function ($Call)
    {
        if ($Call['Location'] != $Call['Session']['Location'])
            F::Run('Session', 'Write', $Call, ['Data' => ['Location' => $Call['Location']]]);

        if (isset($_SERVER['HTTP_REFERER']))
            $Call = F::Apply('System.Interface.Web', 'Redirect', $Call, ['Location' => $_SERVER['HTTP_REFERER']]);
        else
            $Call = F::Apply('System.Interface.Web', 'Redirect', $Call, ['Location' => '/']);

        return $Call;
    });