<?php

    /* Sphinx
     * @author BreathLess
     * @description  
     * @package Sphinx
     * @version 7.2
     */

    self::setFn('Do', function ($Call)
    {
        $Sphinx = new SphinxClient();
        $Sphinx->setServer( 'localhost', 9312 );

        if (!$Status = $Sphinx->status())
            $Call['Output']['Status'][] =
                array(
                    'Type' => 'Block',
                    'Class' => 'alert alert-danger',
                    'Value' => '<l>Sphinx.Connect.Failed</l>'
                );
        else
        {
            foreach ($Status as &$Row)
                $Row[0] = '<l>Sphinx.Status.'.$Row[0].'</l>';

            $Call['Output']['Status'][] =
                array(
                    'Type' => 'Table',
                    'Value' => $Status
                );
        }

        return $Call;
    });

    self::setFn('Reindex', function ($Call)
    {
        $Call['Output']['Content'][] =
                array(
                    'Type' => 'Block',
                    'Class' => 'alert alert-success',
                    'Value' => nl2br(shell_exec('indexer --all --rotate'))
                );

        return $Call;
    });