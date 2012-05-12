<?php

/* Codeine
     * @author BreathLess
     * @description Mixins Support 
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Process', function ($Call)
    {
        if (isset($Call['Mixins']))
            foreach ($Call['Mixins'] as $Mixin)
                $Call = F::Merge($Call, F::loadOptions('Domain.' . $Mixin));

        return $Call;
     });