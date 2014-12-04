<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
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
        if (isset($Call['Data'][0]))
            foreach ($Call['Data'] as $IX => $Object)
                F::Set('GEC:'.$Call['Entity'].':'.$Object['ID'], $Object);
        else
            F::Set('GEC:'.$Call['Entity'].':'.$Call['Data']['ID'], $Call['Data']);

        return $Call;
    });