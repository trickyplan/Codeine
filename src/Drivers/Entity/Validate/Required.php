<?php

    /* Codeine
     * @author BreathLess
     * @description
     * @package Codeine
     * @version 7.x
     */

    setFn('Process', function ($Call)
    {
        if (isset($Call['Node']['Required']) && $Call['Node']['Required'] && !isset($Call['Node']['Nullable']))
        {
            $Produced = F::Merge($Call['Current'][0], $Call['Data']);

            if (F::Dot($Produced, $Call['Name']) === null)
            {
                F::Log('Required key '.$Call['Entity'].'.'.$Call['Name'].' not defined', LOG_ERR);
                return 'Required';
            }
        }

        return true;
    });