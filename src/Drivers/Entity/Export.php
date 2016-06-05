<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Do', function ($Call)
    {
        if (isset($Call['Request']['Fields']))
            $Call['Fields'] = $Call['Request']['Fields'];

        $Elements = F::Run('Entity', 'Read', $Call,
                    [
                         'Entity' => $Call['Entity']
                    ]);

        $Call['View']['Renderer'] =
            [
                'Service' =>  'View.JSON',
                'Method' =>  'Render'
            ];

        foreach ($Elements as $Element)
            $Call['Output']['Content'][] =
            [
                'Type'  => 'Template',
                'Scope' => $Call['Entity'],
                'ID'    => 'Export',
                'Data'  => $Element
            ];

        return $Call;
    });