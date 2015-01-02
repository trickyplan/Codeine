<?php

    /* Codeine
     * @author BreathLess
     * @description Memoize call
     * @package Codeine
     * @version 8.x
     */

    setFn('Run', function ($Call)
    {
        $Hash = [$Call['Run']['Service'], $Call['Run']['Method']];

        foreach ($Call['Run']['Call']['Contract'][($Call['Run']['Method'])]['Call'] as $Key => $Argument)
            if (isset($Call['Run']['Call'][$Key]))
                $Hash[] = $Call['Run']['Call'][$Key];

        $Hash = serialize($Hash);

        $Call['Run']['Call']['Memo'] = true;

        $Result = F::Get($Hash);

        if ($Result === null)
        {
            $Result = F::Execute($Call['Run']['Service'], $Call['Run']['Method'], $Call['Run']['Call']);
            F::Set($Hash, $Result);
        }

        return $Result;
    });