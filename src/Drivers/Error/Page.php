<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.6.2
     */

    self::setFn ('Do', function ($Call)
    {
        $Call['Title'] = $Call['Code'];
        $Call['Description'] = 'TODO';
        $Call['Keywords'] = array ('TODO');

        $Call['Output']['Content'] = array (array (
                                                'Type'  => 'Template',
                                                'Scope' => 'Errors',
                                                'ID' => $Call['Code']
                                            ));
        return $Call;
     });