<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 43.10.3
     */

    setFn('Make', function ($Call)
    {
        if (isset($Call['Attributes']['String']) && !empty($Call['Attributes']['String']))
            foreach ($Call['Attributes']['String'] as $Attribute => $DefaultValue)
            {
                if (isset($Call[$Attribute]))
                {
                    $Call[$Attribute] = F::Live($Call[$Attribute], $Call);

                    if (empty($Call[$Attribute]))
                        ;
                    else
                    {
                        if (is_array($Call[$Attribute]))
                            $Call[$Attribute] = implode(' ', F::Merge($DefaultValue, $Call[$Attribute]));

                        $Attributes[] = strtolower($Attribute).'="'.$Call[$Attribute].'"';
                    }
                }
                else
                {
                    if (empty($DefaultValue))
                        ;
                    else
                    {
                        if (is_array($DefaultValue))
                            $DefaultValue = implode(' ', $DefaultValue);

                        $Attributes[] = strtolower($Attribute).'="'.$DefaultValue.'"';
                    }
                }
            }

        if (isset($Call['Attributes']['Boolean']) && !empty($Call['Attributes']['Boolean']))
            foreach ($Call['Attributes']['Boolean'] as $Attribute => $DefaultValue)
            {
                if (isset($Call[$Attribute]))
                {
                    $Call[$Attribute] = F::Live($Call[$Attribute], $Call);

                    if (empty(F::Dot($Call, $Attribute)))
                        ;
                    else
                        $Attributes[] = strtolower($Attribute);
                }
                else
                    if (!empty($DefaultValue) && $DefaultValue)
                        $Attributes[] = strtolower($Attribute);
            }

        if (isset($Call['Block']) && $Call['Block'])
            $Call['HTML'] = '<'.$Call['Tag'].' '.implode(' ', $Attributes).'>'.$Call['Value'].'</'.$Call['Tag'].'>';
        else
            $Call['HTML'] = '<'.$Call['Tag'].' '.implode(' ', $Attributes).' />';

        return $Call;
    });