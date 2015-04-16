<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */
        require Root.'/vendor/autoload.php';

    setFn('Open', function ($Call)
    {
        $Call['Link'] = new Airbrake\Client(
            new Airbrake\Configuration($Call['Airbrake']['API Key'], $Call['Airbrake']['Options'])
        );
        return $Call;
    });

     setFn('Write', function ($Call)
     {
         $Call = F::Apply(null, 'Open', $Call);

         foreach ($Call['Data'] as $Row)
            $Call['Link']->notifyOnError($Row[3].':'.$Row[2]);

         return true;
     });