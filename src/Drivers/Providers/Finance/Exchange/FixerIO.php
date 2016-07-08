<?php
    
    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
    
    setFn('Get', function ($Call)
    {
        // Default Base is EUR

        $Call['Fixer']['Result'] = F::Run('IO', 'Read', $Call,
            [
                'Storage'           => 'Web',
                'Output Format'     => 'Formats.JSON',
                'IO One'            => true,
                'Where'             => 'http://api.fixer.io/latest'
            ]);

        d(__FILE__, __LINE__, $Call['Fixer']['Result']);

        return $Call;
    });

    setFn('Get.Rates', function ($Call)
    {

        return $Call;
    });