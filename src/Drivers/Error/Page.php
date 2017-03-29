<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Catch', function ($Call)
    {
        $Call['Run'] =
        [
            'Service'   => 'Error.Page',
            'Method'    => 'Do',
            'Call'      =>
            [
                'Code'  => 403
            ]
        ];
        return $Call;
    });
    
    setFn ('Do', function ($Call)
    {
        $Call['HTML']['Page']['Title'] = $Call['Code'];
        $Call['HTML']['Page']['Description'] = 'TODO';
        $Call['HTML']['Page']['Keywords'] = array ('TODO');

        $Call['Layouts'] = [['Scope' => 'Default', 'ID' => 'Main'], ['Scope' => 'Project', 'ID' => 'Zone']];
        
        if (isset($Call['Reason']))
            $Call['Output']['Content'] =
            [
                [
                    'Type'  => 'Template',
                    'Scope' => 'Error/'.$Call['Code'],
                    'ID' => $Call['Reason']
                 ]
            ];
        else
            $Call['Output']['Content'] =
            [
                [
                    'Type'  => 'Template',
                    'Scope' => 'Error',
                    'ID' => $Call['Code']
                 ]
            ];
        $Call['HTTP']['Headers']['HTTP/1.1'] = '404 Not Found';
        return $Call;
     });