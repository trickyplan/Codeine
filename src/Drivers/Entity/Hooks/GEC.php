<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('beforeEntityRead', function ($Call)
    {
        if (isset($Call['Where']['ID']))
        {
            if (is_array($Call['Where']['ID']))
            {
                foreach($Call['Where']['ID'] as $CID => $ID)
                    if (($Call['Data'][$ID] = F::Get('GEC:'.$Call['Entity'].':'.$ID)) !== null)
                    {
                        F::Log('GEC optimizes '.$Call['Entity'].':'.$CID, LOG_GOOD);
                        unset($Call['Where']['ID'][$CID]);
                    }
            }
            else
                if (($Call['Data'][$Call['Where']['ID']]
                    = F::Get('GEC:'.$Call['Entity'].':'.$Call['Where']['ID'])) !== null)
                {
                    F::Log('GEC optimizes '.$Call['Entity'].':'.$Call['Where']['ID'], LOG_GOOD);
                    unset($Call['Where']['ID']);
                }

            if (empty($Call['Where']['ID']) or empty($Call['Where']))
                $Call['Skip Read'] = true;
        }

        return $Call;
    });

    setFn('afterEntityRead', function ($Call)
    {
        if (isset($Call['Fields']))
            ;// Partial load, no save
        else
        {
            if (isset($Call['Data']['ID']))
                F::Set('GEC:'.$Call['Entity'].':'.$Call['Data']['ID'], $Call['Data']);
            else
                foreach ($Call['Data'] as $IX => $Object)
                    if (isset($Object['ID']))
                    F::Set('GEC:'.$Call['Entity'].':'.$Object['ID'], $Object);
        }

        return $Call;
    });