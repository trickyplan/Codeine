<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Make', function ($Call)
    {
        $Call['Radios'] = '';

        $IX = 0;
        foreach($Call['Options'] as $Key => $Value)
        {
             if (isset($Call['Localized']) && $Call['Localized'])
             {
                 if (!isset($Call['Values Locale']))
                     $Call['Values Locale'] = $Call['Entity'].'.Entity:'.$Call['Key'];

                 $lValue = '<l>'.$Call['Values Locale'].'.'.$Value.'</l>';
             }
             else
                $lValue = $Value;

            if (isset($Call['Keys as values']) && $Call['Keys as values'])
            {
                 $lValue = $Value;
                 $Value = $Key;
            }

            $Call['Radios'] .= F::Run('View', 'Load',
                [
                    'Scope' => $Call['View']['HTML']['Widget Set'].'/Widgets',
                    'ID' => 'Form/Radio',
                    'Data' =>
                    F::Merge ($Call,
                        [
                            'IX' => $IX,
                            'Label' => $lValue,
                            'Value' => $Value,
                            'Checked' => ($Value == $Call['Value'] ? 'checked': '')
                        ])
                ]
            );
            $IX++;
        }

        return $Call;
     });