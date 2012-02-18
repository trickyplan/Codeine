<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.1
     */

    self::setFn('Inject', function ($Call)
    {
        if(isset($Call['Title']))
            $Call['Output']['Title'][]
                = array (
                'Type'  => 'Page.Title',
                'Value' => $Call['Title']
            );
        else
            $Call['Output']['Title'][]
                = array (
                'Type'  => 'Block',
                'Class' => 'warning',
                'Value' => 'Заголовок страницы не задан.' // FIXME
            );

        if (isset($Call['Description']))
            $Call['Output']['Meta'][]
                = array (
                'Type'  => 'Page.Description',
                'Value' => $Call['Description']
            );
        else
            $Call['Output']['Meta'][]
                = array (
                'Type'  => 'Block',
                'Class' => 'warning',
                'Value' => 'Описание страницы не задано.' // FIXME
            );

        if (isset($Call['Keywords']))
            $Call['Output']['Meta'][]
                = array (
                'Type'  => 'Page.Keywords',
                'Value' => $Call['Keywords']
            );
        else
            $Call['Output']['Meta'][]
                = array (
                'Type'  => 'Block',
                'Class' => 'warning',
                'Value' => 'Ключевые слова страницы не заданы.' // FIXME
            );


         return $Call;
     });