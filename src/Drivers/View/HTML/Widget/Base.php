<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 43.10.3
     */

    setFn('Make', function ($Call) {
        if (isset($Call['Attributes']['String']) && !empty($Call['Attributes']['String'])) {
            foreach ($Call['Attributes']['String'] as $Attribute => $DefaultValue) {
                if (isset($Call[$Attribute])) {
                    $Call[$Attribute] = F::Live($Call[$Attribute], $Call);

                    if (empty($Call[$Attribute])) {
                        ;
                    } else {
                        if (is_array($Call[$Attribute])) {
                            $Call[$Attribute] = implode(' ', F::Merge($DefaultValue, $Call[$Attribute]));
                        }

                        $Attributes[] = strtolower($Attribute) . '="' . $Call[$Attribute] . '"';
                    }
                } else {
                    if (empty($DefaultValue)) {
                        ;
                    } else {
                        if (is_array($DefaultValue)) {
                            $DefaultValue = implode(' ', $DefaultValue);
                        }

                        $Attributes[] = strtolower($Attribute) . '="' . $DefaultValue . '"';
                    }
                }
            }
        }

        if (isset($Call['Attributes']['Boolean']) && !empty($Call['Attributes']['Boolean'])) {
            foreach ($Call['Attributes']['Boolean'] as $Attribute => $DefaultValue) {
                if (isset($Call[$Attribute])) {
                    $Call[$Attribute] = F::Live($Call[$Attribute], $Call);

                    if (empty(F::Dot($Call, $Attribute))) {
                        ;
                    } else {
                        $Attributes[] = strtolower($Attribute);
                    }
                } else {
                    if (!empty($DefaultValue) && $DefaultValue) {
                        $Attributes[] = strtolower($Attribute);
                    }
                }
            }
        }

        if (empty($Attributes)) {
            $Attributes = '';
        } else {
            $Attributes = ' ' . implode(' ', $Attributes);
        }

        $Block = false;

        if (F::Dot($Call, 'Block')) {
            $Call = F::Dot($Call, 'Closing Tag', F::Dot($Call, 'Block'));
            F::Log('"Block" flag is deprecated. Use "Closing Tag" instead', LOG_WARNING, ['Developer', 'Deprecated']);
        }

        if (F::Dot($Call, 'Closing Tag')) {
            $Call['HTML'] = '<' . $Call['Tag'] . $Attributes . '>' . $Call['Value'] . '</' . $Call['Tag'] . '>';
        } else {
            $Call['HTML'] = '<' . $Call['Tag'] . $Attributes . ' />';
        }

        return $Call;
    });