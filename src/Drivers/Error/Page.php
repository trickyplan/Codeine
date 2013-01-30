<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn ('Do', function ($Call)
    {
        $Call['Title'] = $Call['Code'];
        $Call['Description'] = 'TODO';
        $Call['Keywords'] = array ('TODO');

        $Call['Layouts'] = [['Scope' => 'Default', 'ID' => 'Main'], ['Scope' => 'Project', 'ID' => 'Zone']];

        $Call['Output']['Content'] = [[
                                        'Type'  => 'Template',
                                        'Scope' => 'Errors',
                                        'ID' => $Call['Code']
                                     ]];
        return $Call;
     });