<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::Fn ('Add', function ($Call)
        {
            $Locale = 'Russian';

            // FIXME Codeinize
            return F::Run(
                    array(
                        'Object' => array('Node.Add', 'Language'),
                        'ID' => $Locale,
                        'Key' => $Call['Key'],
                        'Value' => $Call['Value']
                    )
                );
        });