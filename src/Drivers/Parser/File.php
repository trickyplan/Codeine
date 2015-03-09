<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Markup = file_get_contents($Call['Filename']);
        $Call = F::Run('Parser', 'Do', $Call, ['Markup' => $Markup]);

        $Call['View']['Renderer'] = ['Service' => 'View.JSON', 'Method' => 'Render'];
        $Call['Output']['Content'][] = $Call['Data'];

        return $Call;
    });