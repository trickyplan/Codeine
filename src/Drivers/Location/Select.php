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

        if ($Call['HTTP']['URL'] == '/')
            $Call['HTTP']['URL'] = '';

        foreach ($Locations as $Location)
        {

            if (null === $Call['Location']['Slug'])
                $Location['URL'] = '/'.$Location['Slug'];
            else
            {
                if (preg_match('@^/'.$Call['Location']['Slug'].'@Ssuu', $Call['HTTP']['URL']))
                    $Location['URL'] = str_replace($Call['Location']['Slug'], $Location['Slug'], $Call['HTTP']['URL']);
                else
                    $Location['URL'] = '/'.$Location['Slug'];
            }

            $Location['URL'] = $Call['HTTP']['Proto'].$Call['HTTP']['Host'].$Location['URL'];

            if ($Location['ID'] == $Call['Location']['ID'])
                $Call['Output']['Content'][] =
                [
                    'Type' => 'Template',
                    'Scope' => 'Location',
                    'ID' => 'Show/Widget.Selected',
                    'Data' => $Location
                ];
            else
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
            $Call = F::Apply('System.Interface.HTTP', 'Redirect', $Call, ['Location' => $_SERVER['HTTP_REFERER']]);
        else
            $Call = F::Apply('System.Interface.HTTP', 'Redirect', $Call, ['Location' => '/']);

        return $Call;
    });