<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.6.2
     */

    self::setFn ('Page', function ($Call)
    {
        $Call['Headers']['HTTP/1.0'] = '404 Not Found';

        $Call['Title'] = '404';
        $Call['Description'] = 'TODO';
        $Call['Keywords'] = array ('TODO');

        $Call['Output']['Content'] = array (array (
                                                'Type'  => 'Template',
                                                'Scope' => 'Errors',
                                                'ID' => '404',
                                                'Data' => array(
                                                    'Suggestion' => F::Live($Call['Prediction'], $Call)
                                                )
                                            ));
        return $Call;
     });

        self::setFn('Block', function ($Call)
        {
            $Call['Output']['Content'] = array (array (
                                                'Type'  => 'Template',
                                                'Scope' => 'Errors/Blocks',
                                                'ID' => '404',
                                                'Data' => array(
                                                    'Suggestion' => F::Live($Call['Prediction'], $Call)
                                                )
                                            ));
            return $Call;
        });