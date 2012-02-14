<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.1
     */

    self::setFn ('Get', function ($Call)
    {
        $Hash = array();

        foreach ($Call['IDs'] as $JSFile)
        {
            $Hash[] = $JSFile.F::Run('Engine.IO', 'Execute', array(
                                                 'Storage' => 'JS',
                                                 'Execute' => 'Version',
                                                 'Where' =>
                                                     array(
                                                         'ID' => $JSFile
                                                     )
                                            ));
        }

        return sha1(implode('',$Hash));
    });