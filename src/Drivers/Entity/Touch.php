<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Hook('beforeUpdateDo', $Call);

        $Call['Purpose'] = 'Update';
        $Call['Where'] = F::Live($Call['Where']); // FIXME

        $Call = F::Hook('beforeUpdatePost', $Call);

        // Вызываем Entity.Update без $Data

        unset ($Call['Data']);

        $Elements = F::Run('Entity', 'Read', $Call);

        foreach ($Elements as $Element)
            F::Run('Entity', 'Update', $Call, ['Where' => $Element['ID']]);

        return $Call;
    });