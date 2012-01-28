<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.0
     */

    self::setFn ('Make', function ($Call)
        {
            $Call['Layout'] = F::Run (array(
                                           '_N'      => 'Engine.View',
                                           '_F'      => 'Load',
                                           'ID'      => $Call['ID']
                                      ));
            return $Call['Layout'];
        });