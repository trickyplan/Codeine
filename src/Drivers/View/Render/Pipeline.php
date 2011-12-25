<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.0
     */

    self::setFn ('Process', function ($Call)
        {
            $Call['Places'] = array();

            if (is_array($Call['Value']))
            {
                foreach ($Call['Value'] as $Widget)
                {
                    if (!isset($Call['Places'][$Widget['Place']]))
                        $Call['Places'][$Widget['Place']] = '';

                    $Call['Places'][$Widget['Place']] .=
                        F::Run ($Call['Renderer'].'.' . $Widget['Type'], 'Make', $Widget);
                }
            }

            $Call['Output'] = $Call['Layout'];

            foreach ($Call['Places'] as $Place => $Body)
                $Call['Output'] = str_replace ('<place>' . $Place . '</place>', $Body, $Call['Output']);

            return $Call;
        });