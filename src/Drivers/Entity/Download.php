<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Before', function ($Call)
    {
        $Call['Where'] = F::Live($Call['Where']);

        $Call['Data'] = F::Run('Entity', 'Read', $Call, ['One' => true, 'Limit' => ['From' => 0, 'To' => 1]]);

        return $Call;
    });

    setFn('Do', function ($Call)
    {
        $Call['Output']['Content'] = F::findFile('Data/'.$Call['Entity'].'/File/'.$Call['Data']['File']);

        return $Call;
    });