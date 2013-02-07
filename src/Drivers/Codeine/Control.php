<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $LastNews = simplexml_load_string(file_get_contents('http://one2team.ru/blog/category/Codeine.rss'));

        foreach ($LastNews->channel->item as $item)
        {
            $Table[] = ['<a href="'.$item->link.'">'.$item->title.'</a>'];
        }

        $Call['Output']['Content'][] =
        [
            'Type' => 'Table',
            'Value' => $Table
        ];

        return $Call;
    });


    setFn('Version', function ($Call)
    {
        $Current = file_get_contents('https://raw.github.com/Breathless/Codeine/master/docs/VERSION');

        $Call['Output']['Content'][] =
             [
                 'Type'  => 'Block',
                 'Class' => 'alert alert-info',
                 'Value' => '<l>Codeine.Control:Version.Actual</l>:<br/> ' . $Current
             ];

        $Call['Output']['Content'][] =
            [
                'Type'  => 'Block',
                'Class' => 'alert ' . ($Current > $Call['Version']['Codeine']['Major'] ? 'alert-error' : 'alert-success'),
                'Value' => '<l>Codeine.Control:Version.Installed</l>: <br/>'.$Call['Version']['Codeine']['Major']
            ];

        return $Call;
    });