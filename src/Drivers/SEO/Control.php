<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {


        return $Call;
    });

    setFn('Robots', function ($Call)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if (file_put_contents(Root.'/Public/robots.txt', $Call['Request']['Robots']) !== false)
                $Call['Output']['Message'][] = ['Type' => 'Block', 'Class' => 'alert alert-success', 'Value' => 'Обновлено'];
            else
                $Call['Output']['Message'][] = ['Type' => 'Block', 'Class' => 'alert alert-danger', 'Value' => 'Обновление не удалось'];
        }

        $Call['Robots'] = file_get_contents(Root.'/Public/robots.txt');

        return $Call;
    });

    setFn('Sitemaps', function ($Call)
    {
        $Call['Output']['Content'][] = ['Type' => 'Block', 'Class' => 'alert', 'Value' => 'Блок в разработке.'];

        return $Call;
    });