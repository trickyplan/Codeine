<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Catch', function ($Call)
    {
        $Call['Run'] = '/error/'.$Call['Code'];
        return $Call;
    });
    
    setFn ('Do', function ($Call)
    {
        $Call['Page']['Title'] = $Call['Code'];
        $Call['Page']['Description'] = 'TODO';
        $Call['Page']['Keywords'] = array ('TODO');

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
        
        $Call['Skip Front'] = true;
        
        return $Call;
     });