<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::setFn ('Page', function ($Call)
    {
        $Call['Headers']['HTTP/1.0'] = '404 Not Found';
        $Call['Value'] = array();

        $Call['Value'][] =
            array(
                'Place' => 'Title',
                'Type'  => 'Page.Title',
                'Value' => $Call['Title']
            );

        $Call['Value'][] =
            array(
                'Place' => 'Meta',
                'Type'  => 'Page.Description',
                'Value' => $Call['Description']
            );

        $Call['Value'][] =
            array(
                'Place' => 'Meta',
                'Type'  => 'Page.Keywords',
                'Value' => $Call['Keywords']
            );

        $Call['Value'][] =
            array(
                'Place' => 'Content',
                'Type'  => 'Static',
                'Value' => 'Error/404'
            );

         return $Call;
     });