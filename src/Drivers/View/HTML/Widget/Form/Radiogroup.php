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
        {
            if (isset($Call['Localized']) && $Call['Localized'])
                $lValue = '<l>'.$Call['Entity'].'.Entity:'.$Call['Key'].'.'.$Value.'</l>';
            else
                $lValue = $Value;

            $Call['Radios'] .= F::Run('View', 'Load',
                [
                    'Scope' => $Call['Widget Set'].'/Widgets',
                    'ID' => 'Form/Radio',
                    'Data' =>
                    F::Merge ($Call,
                        [
                            'Label' => $lValue,
                            'Value' => $Value,
                            'Checked' => ($IX == $Call['Value'] ? 'checked': '')
                        ])
                ]
            );
        }

        return $Call;
     });