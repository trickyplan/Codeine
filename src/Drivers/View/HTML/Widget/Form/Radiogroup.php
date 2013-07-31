<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Make', function ($Call)
    {
        $Call['Radios'] = '';
        foreach($Call['Options'] as $IX => $Value)
            $Call['Radios'] .= F::Run('View', 'Load',
                    [
                        'Scope' => $Call['Widget Set'].'/Widgets',
                        'ID' => 'Form/Radio',
                        'Data' =>
                            F::Merge ($Call,
                                [
                                    'Value' => $Value,
                                    'Checked' => ($IX == $Call['Value']? 'checked': '')
                                ])
                    ]
                    );

        return $Call;
     });