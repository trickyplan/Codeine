<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.0
     */

    self::setFn('Inject', function ($Call)
    {
        if(isset($Call['Title']))
            $Call['Output']['Title'][]
                = array (
                'Type'  => 'Page.Title',
                'Value' => $Call['Title']
            );

        if (isset($Call['Description']))
            $Call['Output']['Meta'][]
                = array (
                'Type'  => 'Page.Description',
                'Value' => $Call['Description']
            );

        if (isset($Call['Keywords']))
            $Call['Output']['Meta'][]
                = array (
                'Type'  => 'Page.Keywords',
                'Value' => $Call['Keywords']
            );


         return $Call;
     });