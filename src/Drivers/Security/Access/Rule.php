<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Check', function ($Call)
    {

        foreach ($Call['Rules'] as $Name => $Rule)
        {
            if (isset($Rule['Debug']))
            {
                d(__FILE__, __LINE__, F::Diff($Rule['Run'], $Call));
            }

            if ($Rule['Weight'] >= $Call['Weight'])
            {
                if (isset($Rule['Run']) && (F::Diff($Rule['Run'], $Call) === null))
                {
                    if (!isset($Rule['Expression']) || F::Live($Rule['Expression'], $Call))
                    {
                        F::Log('Rule «'.$Name.'» applied');
                        $Call['Decision'] = $Rule['Decision'];
                        $Call['Weight'] = $Rule['Weight'];
                        $Call['Rule'] = $Rule;
                    }
                }
                else
                {
                    F::Log('Rule «'.$Name.'» tested');
                }

                if (isset($Rule['Run']['Service']) && isset($Rule['Run']['Method']) && isset($Rule['Message']))
                    if (($Call['Service'] == $Rule['Run']['Service']) && ($Call['Method'] == $Rule['Run']['Method']))
                        $Call['Message'] = $Rule['Message'];
            }
            else
                F::Log('Rule «'.$Name.'» skipped ('.$Rule['Weight'].'<'.$Call['Weight'].')');
        }

        F::Log('Final decision:'. $Call['Decision']);
        return $Call;
     });