<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        if (isset($Call['Session']['User']) && ($Call['Session']['User'] > 0))
            $Call['Output']['Content'][] =
                [
                    'Type' => 'Template',
                    'Scope' => 'User',
                    'ID' => 'Show/Cabinet',
                    'Data' => $Call['Session']['User']
                ];
        else
            $Call['Output']['Content'][] =
                [
                    'Type' => 'Template',
                    'Scope' => 'Session',
                    'ID' => 'Show/Cabinet',
                    'Data' => $Call['Session']
                ];

         return $Call;
    });