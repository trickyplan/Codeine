<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn ('Page', function ($Call)
    {
        $Call['Headers']['HTTP/1.0'] = '500 Internal Server Error';

        $Call['Title'] = '500';
        $Call['Description'] = 'TODO';
        $Call['Keywords'] = array ('TODO');

        $Call['Output']['Content'] = array (array (
                                                'Type'  => 'Template',
                                                'Scope' => 'Error',
                                                'Value' => '500',
                                                'Data' => array(

                                                )
                                            ));
        return $Call;
     });