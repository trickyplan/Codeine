<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::setFn ('Process', function ($Call)
        {
            $Call['Places'] = array();

            if (is_array($Call['Widgets']))
            {
                foreach ($Call['Widgets'] as $Widget)
                {
                    if (!isset($Call['Places'][$Widget['Place']]))
                        $Call['Places'][$Widget['Place']] = '';

                    $Call['Places'][$Widget['Place']] .=
                        F::Run ($Widget,
                                array(
                                     '_N' => 'View.UI.HTML.' . $Widget['Type'], // FIXME
                                     '_F' => 'Make'
                                )
                        );
                }
            }

            $Call['Output'] = $Call['Layout'];

            foreach ($Call['Places'] as $Place => $Body)
                $Call['Output'] = str_replace ('<place>' . $Place . '</place>', $Body, $Call['Output']);

            return $Call;
        });