<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description
     * @package Codeine
     * @version 8.x
     */

    setFn('Process', function ($Call)
    {
        if (isset($Call['Node']['Required']) && $Call['Node']['Required'] && !isset($Call['Node']['Nullable']))
        {
            if (isset($Call['Current'][0]))
                $Produced = F::Merge($Call['Current'][0], $Call['Data']);
            else
                $Produced = $Call['Data'];

            if (F::Dot($Produced, $Call['Name']) === null)
            {
                F::Log('Required key '.$Call['Entity'].'.'.$Call['Name'].' not defined', LOG_ERR);
                return 'Required';
            }
        }

        return true;
    });