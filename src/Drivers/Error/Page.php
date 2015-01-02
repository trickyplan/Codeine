<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn ('Do', function ($Call)
    {
        $Call['Page']['Title'] = $Call['Code'];
        $Call['Page']['Description'] = 'TODO';
        $Call['Page']['Keywords'] = array ('TODO');

        $Call['Layouts'] = [['Scope' => 'Default', 'ID' => 'Main'], ['Scope' => 'Project', 'ID' => 'Zone']];

        $Call['Output']['Content'] = [[
                                        'Type'  => 'Template',
                                        'Scope' => 'Error',
                                        'ID' => $Call['Code']
                                     ]];
        return $Call;
     });