<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn ('Get', function ($Call)
    {
        $Hash = array();

        foreach ($Call['IDs'] as $IX => $JSFile)
        {
            $Hash[$IX] = $JSFile.F::Run('IO', 'Execute', array(
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