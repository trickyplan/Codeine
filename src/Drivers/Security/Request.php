<?php

    /* Codeine
     * @author BreathLess
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Filter', function ($Call)
    {
        if (in_array($Call['HTTP']['URL'], $Call['URL Filters']))
            $Call = F::Hook('onRequestBlocked', $Call);
        else
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