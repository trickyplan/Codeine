<?php

    /* Sphinx
     * @author BreathLess
     * @description  
     * @package Sphinx
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Sphinx = new SphinxClient();
        $Sphinx->setServer( 'localhost', 9312 );

        if (!$Status = $Sphinx->status())
            $Call['Output']['Status'][] =
                [
                    'Type' => 'Block',
                    'Class' => 'alert alert-danger',
                    'Value' => '<l>Sphinx.Connect.Failed</l>'
                ];
        else
        {
            foreach ($Status as &$Row)
                $Row[0] = '<l>Sphinx.Status.'.$Row[0].'</l>';

            $Call['Output']['Status'][] =
                [
                    'Type' => 'Table',
                    'Value' => $Status
                ];
        }

        return $Call;
    });

    setFn('Reindex', function ($Call)
    {
        $Call['Output']['Content'][] =
                [
                    'Type' => 'Block',
                    'Class' => 'alert alert-success',
                    'Value' => nl2br(shell_exec('indexer --all --rotate'))
                ];

        return $Call;
    });