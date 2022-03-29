<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: Standart MD5
     * @package Codeine
     * @version 8.x
     * @date 22.11.10
     * @time 4:40
     */

    setFn('Get', function ($Call) {
        return hash(F::Dot($Call, 'Security.Hash.PHP.Algo'), $Call['Value'] . F::Dot($Call, 'Security.Hash.Salt'));
    });

    setFn('Test', function ($Call) {
        $Algorithms = hash_algos();
        $Results = [];
        foreach ($Algorithms as $Algo) {
            F::Start($Algo);
            for ($IX = 0; $IX < 1000; $IX++) {
                $Hash = hash($Algo, $Call['Value']);
            }

            F::Stop($Algo);
            $Results[$Algo] = F::Time($Algo);
        }
        asort($Results);

        return $Results;
    });
