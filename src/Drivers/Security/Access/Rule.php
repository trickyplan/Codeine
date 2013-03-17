<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Check', function ($Call)
    {
        if (isset($Call['Rules']) && is_array($Call['Rules']))
        {
            F::Log(count($Call['Rules']).' rules loaded', LOG_INFO);

            foreach ($Call['Rules'] as $Name => $Rule)
            {
                if ($Rule['Weight'] >= $Call['Weight'])
                {
                    if (isset($Rule['Debug']))
                    {
                        d(__FILE__, __LINE__, $Rule['Run']);
                        d(__FILE__, __LINE__, F::Diff($Call, $Rule['Run']));
                    }

                    if (isset($Rule['Run']) && (F::Diff($Rule['Run'], $Call) === null))
                    {
                        if (!isset($Rule['Expression']) || F::Live($Rule['Expression'], $Call))
                        {
                            F::Log('Rule '.$Name.' applied', LOG_DEBUG);
                            $Call['Decision'] = $Rule['Decision'];
                            $Call['Weight'] = $Rule['Weight'];
                            $Call['Rule'] = $Rule;
                        }
                    }

                    if (isset($Call['Service']) && isset($Rule['Run']['Service']) && isset($Rule['Run']['Method']) && isset($Rule['Message']))
                        if (($Call['Service'] == $Rule['Run']['Service']) && ($Call['Method'] == $Rule['Run']['Method']))
                            $Call['Message'] = $Rule['Message'];
                }
            }
        }
        else
            F::Log('No rules loaded', LOG_WARNING);

        if (!isset($Call['Rule']))
            F::Log('No one rule applied', LOG_INFO);

        F::Log('Final decision:'. ($Call['Decision']? 'Allow': 'Deny'), LOG_INFO);

        unset($Call['Rules']);
        return $Call;
     });