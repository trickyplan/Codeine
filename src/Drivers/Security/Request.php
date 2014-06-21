<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Filter', function ($Call)
    {
        if (isset($Call['Request']))
            foreach ($Call['Request Filters'] as $Filter)
                foreach ($Filter['Match'] as $Match)
                    if (F::Diff($Match, $Call['Request']) === null)
                    {
                        if ($Filter['Decision'])
                            ;
                        else
                            $Call = F::Hook('onRequestBlocked', $Call);
                    }

        return $Call;
    });