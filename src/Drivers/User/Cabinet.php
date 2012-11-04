<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        if (isset($Call['Session']['User']) && ($Call['Session']['User'] != -1))
            $Call['Output']['Content'][] =
                array(
                    'Type' => 'Template',
                    'Scope' => 'User',
                    'ID' => 'Show/Cabinet',
                    'Data' => $Call['Session']['User']
                );
        else
            $Call['Output']['Content'][] =
                array(
                    'Type' => 'Template',
                    'Scope' => 'User',
                    'ID' => 'Guest'
                );

         return $Call;
    });