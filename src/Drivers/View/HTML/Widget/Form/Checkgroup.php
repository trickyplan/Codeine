<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Make', function ($Call)
    {
        $Call['Checks'] = '';

        $Call['Name'] .= '[]';

        foreach ($Call['Options'] as $Key => $Value)
        {
            if (isset($Call['Localized']) && $Call['Localized'])
                $lValue = '<l>'.$Call['Entity'].'.Entity:'.$Call['Key'].'.'.$Value.'</l>';
            else
                $lValue = $Value;

            $Checked = (
                $Key == $Call['Value']
                ||
                $Value == $Call['Value']
                || (is_array($Call['Value'])
                && in_array($Value, $Call['Value'])));

            $Call['Checks'] .= F::Run('View', 'Load',
                [
                    'Scope' => $Call['View']['HTML']['Widget Set'].'/Widgets',
                    'ID' => 'Form/Checkgroup/Checkbox',
                    'Data' =>
                    F::Merge ($Call,
                        [
                            'Value' => $Value,
                            'Checked' => $Checked,
                            'Label' => $lValue,
                            'Checked' => $Checked ? 'checked': ''
                        ])
                ]
            );
        }

        return $Call;
     });