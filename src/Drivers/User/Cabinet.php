<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Do', function ($Call)
    {
        if (isset($Call['Session']['User']))
            $Call['Output']['Content'][] =
                array(
                    'Type' => 'Template',
                    'Scope' => 'User',
                    'Value' => 'Show/Cabinet',
                    'Data' => $Call['Session']['User']
                );
        else
            $Call['Output']['Content'][] =
                array(
                    'Type' => 'Template',
                    'Scope' => 'User',
                    'Value' => 'Guest'
                );

         return $Call;
    });