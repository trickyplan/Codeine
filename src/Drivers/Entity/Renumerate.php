<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Elements = F::Run('Entity', 'Read', $Call);

        F::Run('Entity', 'Delete', $Call);

        foreach ($Elements as $Element)
        {
            unset($Element['ID']);
            F::Run('Entity', 'Create', $Call, ['One' => true, 'Data' => $Element]);
        }

        return $Call;
    });