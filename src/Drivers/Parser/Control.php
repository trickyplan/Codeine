<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Layouts'][] =
            [
                'Scope' => 'Parser',
                'ID' => 'Control'
            ];

        return $Call;
    });

    setFn('URL', function ($Call)
    {
       return F::Run('Parser.URL', 'Do', $Call);
    });

    setFn('Numbered', function ($Call)
    {
       return F::Run('Parser.Numbered', 'Do', $Call);
    });

    setFn('Spider', function ($Call)
    {
       return F::Run('Parser.Spider', 'Do', $Call);
    });