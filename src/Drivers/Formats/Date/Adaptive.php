<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Date() engine 
     * @package Codeine
     * @version 8.x
     */

    setFn('Format', function ($Call)
    {
        $Call['Now'] = time();
        $Output = '';

        if (is_numeric($Call['Value']))
        {
            foreach ($Call['Formats']['Date']['Adaptive']['Segments'] as $Segment => $Format)
            {
                $ValueSegment = date($Segment, $Call['Value']);
                if (date($Segment, $Call['Now']) != $ValueSegment)
                {
                    $Output = date($Format, $Call['Value']);
                    break;
                }
            }

            $Output .= date($Call['Formats']['Date']['Adaptive']['Postfix'], $Call['Value']);
        }

        return $Output;
     });