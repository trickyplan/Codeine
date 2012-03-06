<?php

/* Codeine
     * @author BreathLess
     * @description Mixins Support 
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Process', function ($Call)
    {
        if (isset($Call['Model']['Mixins']))
            foreach ($Call['Model']['Mixins'] as $Mixin)
                $Call['Model'] = F::Merge($Call['Model'], F::loadOptions('Entity.' . $Mixin));

         return $Call['Model'];
     });