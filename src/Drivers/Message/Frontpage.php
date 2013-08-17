<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Run('Entity', 'Load', $Call, ['Entity' => 'Message']);

        foreach ($Call['Folders'] as $Folder)
            $Call['Output']['Content'][] =
            [
                'Type' => 'Block',
                'Class' => 'block',
                'Value' => '<a href="/messages/'.strtolower($Folder).'"><l>Message.Folders:'.$Folder.'Title</l></a>'
            ];

        return $Call;
    });