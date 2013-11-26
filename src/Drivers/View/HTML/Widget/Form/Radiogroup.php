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
             {
                 if (!isset($Call['Values Locale']))
                     $Call['Values Locale'] = $Call['Entity'].'.Entity:'.$Call['Key'];

                 $lValue = '<l>'.$Call['Values Locale'].'.'.$Value.'</l>';
             }
             else
                $lValue = $Value;

            $Call['Radios'] .= F::Run('View', 'Load',
                [
                    'Scope' => $Call['View']['HTML']['Widget Set'].'/Widgets',
                    'ID' => 'Form/Radio',
                    'Data' =>
                    F::Merge ($Call,
                        [
                            'Label' => $lValue,
                            'Value' => $Value,
                            'Checked' => ($Value == $Call['Value'] ? 'checked': '')
                        ])
                ]
            );
        }

        return $Call;
     });