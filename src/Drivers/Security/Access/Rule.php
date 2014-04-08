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
            foreach ($Call['Rules'] as $Name => $Rule)
            {
                if (isset($Rule['Run']))
                    foreach ($Rule['Run'] as &$Node)
                        $Node = F::Live($Node, $Call);

                if (!isset($Rule['Weight']))
                    $Rule['Weight'] = $Call['Weight'];

                if ($Rule['Weight'] >= $Call['Weight'])
                {
                    if (isset($Rule['Run']) && (F::Diff($Rule['Run'], $Call) === null))
                    {
                        if (!isset($Rule['Expression']) || F::Live($Rule['Expression'], $Call))
                        {
                            F::Log('Rule '.$Name.' applied', LOG_DEBUG, 'Security');

                            $Call['Decision'] = $Rule['Decision'];
                            $Call['Weight'] = $Rule['Weight'];
                            $Call['Rule'] = $Name;
                        }
                    }
                    else
                        if (isset($Rule['Debug']))
                            F::Log('Diff '.$Name.' (Rule, Call)'.j(F::Diff($Rule['Run'], $Call)), LOG_INFO, 'Security');

                    if (isset($Call['Service'])
                        && isset($Rule['Run']['Service'])
                        && isset($Rule['Run']['Method'])
                        && isset($Rule['Message']))

                    if (($Call['Service'] == $Rule['Run']['Service'])
                        && ($Call['Method'] == $Rule['Run']['Method']))
                        $Call['Message'] = $Rule['Message'];
                }
            }
        }
        else
            F::Log('No rules loaded', LOG_WARNING);

        if (isset($Call['Rule']))
            F::Log('Rule *'.$Call['Rule'].'* decision *'.$Call['Decision'].'* with weight '.$Call['Weight'], LOG_INFO, 'Security');

        return $Call;
     });