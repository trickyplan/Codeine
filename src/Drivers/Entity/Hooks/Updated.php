<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Process', function ($Call)
    {
        if (isset($Call['Current']))
        {
            if ($Call['Updates'] === null)
            {
                F::Log('Data is null, imaginary update for '.$Call['Current']['ID'], LOG_NOTICE, 'Developer');
                $Call['Data'] = $Call['Current'];
            }
            else
            {
                foreach ($Call['Nodes'] as $Name => $Node)
                {
                    $UpdatedValue = F::Dot($Call['Updates'], $Name);

                    if (null === $UpdatedValue)
                    {
                        if (F::Dot($Node, 'Nullable'))
                            $Call['Data'] = F::Dot($Call['Data'], $Name, null);
                        else
                            $Call['Data'] = F::Dot($Call['Data'], $Name, F::Dot($Call['Current'], $Name));
                    }
                    else
                        $Call['Data'] = F::Dot($Call['Data'], $Name, $UpdatedValue);
                }
            }
        }

        return $Call;
    });