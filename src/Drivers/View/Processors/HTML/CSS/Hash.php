<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.0
     */

    self::setFn ('Get', function ($Call)
    {
        $Hash = array();

        foreach ($Call['IDs'] as $CSSFile)
        {
            $Hash[] = $CSSFile.F::Run('Engine.IO', 'Execute', array(
                                                 'Storage' => 'CSS',
                                                 'Execute' => 'Version',
                                                 'Where' =>
                                                     array(
                                                         'ID' => $CSSFile
                                                     )
                                            ));
        }

        return sha1(implode('',$Hash));
    });