<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Make', function ($Call)
    {
        foreach ($Call['Options'] as $TabID => $TabContent)
        {
            $Call['Tabs']['Headers'][] = F::Run ('View', 'Load', $Call,
                     [
                         'Scope' => $Call['View']['HTML']['Widget Set'].'/Widgets',
                         'ID'    => 'Tabbable/Header',
                         'Data'  =>
                         [
                             'Tab' =>
                             [
                                 'ID' => $TabID
                             ]
                         ]
                     ]);

            $Call['Tabs']['Content'][] = F::Run ('View', 'Load', $Call,
                     [
                         'Scope' => $Call['View']['HTML']['Widget Set'].'/Widgets',
                         'ID'    => 'Tabbable/Content',
                         'Data'  =>
                         [
                             'Tab' =>
                             [
                                 'ID' => $TabID
                             ]
                         ]
                     ]);
        }
        $Call['Tabs']['Headers'] = implode(PHP_EOL, $Call['Tabs']['Headers']);
        $Call['Tabs']['Content'] = implode(PHP_EOL, $Call['Tabs']['Content']);
        return $Call;
    });