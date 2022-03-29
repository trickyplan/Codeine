<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call) {
        $Call['Output']['Content'] =
            [
                [
                    'Type' => 'Block',
                    'Value' => 'Codeine Version: '
                        . F::$_Options['Version']['Codeine']
                ],
                [
                    'Type' => 'Block',
                    'Value' => 'Project Version: '
                        . F::$_Options['Version']['Project']
                ]
            ];
        return $Call;
    });