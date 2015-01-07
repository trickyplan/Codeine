<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Do', function ($Call)
    {
        return F::Run(null, $Call['HTTP']['Method'], $Call);
    });

    setFn('GET', function ($Call)
    {
        $Call['Layouts'][] =
        [
            'Scope' => 'Parser/Control',
            'ID' => 'URL'
        ];

        return $Call;
    });

    setFn('POST', function ($Call)
    {
        $Call['URL'] = $Call['Request']['Data']['URL'];
        $Result = F::Run('IO', 'Read',
        [
            'Storage' => 'Web',
            'Where'   =>
            [
                'ID' => $Call['URL']
            ]
        ]);

        $Result = array_pop($Result);

        if ($Call['Schema'] = F::Run('Parser', 'Discovery', $Call))
            $Call = F::Run('Parser', 'Do', $Call, ['Markup' => $Result]);
        else
            $Call['Data'] = null;

        $Call['View']['Renderer'] = ['Service' => 'View.JSON', 'Method' => 'Render'];
        $Call['Output']['Content'][] = $Call['Data'];

        return $Call;
    });