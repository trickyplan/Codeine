<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('Run', function ($Call)
    {
        F::Log('API Call *'.$Call['Run']['Service'].':'.$Call['Run']['Method'].'* started', LOG_NOTICE);

        $Call = F::Hook('beforeAPIRun', $Call); // В этом месте, практически всегда, происходит роутинг.

            if (F::Dot($Call, 'API.Run.'.$Call['Run']['Service'].'.'.$Call['Run']['Method'].'.Allowed'))
            {
                $AllowedCall = F::Dot($Call, 'API.Run.'.$Call['Run']['Service'].'.'.$Call['Run']['Method'].'.Params');
                
                $Call['Run']['Call'] = $Call['Request'];
                $Run = [];
                foreach ($AllowedCall as $Key)
                    if (isset($Call['Run']['Call'][$Key]))
                        $Run[$Key] = $Call['Run']['Call'][$Key];
                    
                $Call = F::Run($Call['Run']['Service'], $Call['Run']['Method'], $Call,
                    $Run);
            }

        $Call = F::Hook('afterAPIRun', $Call); // А здесь - рендеринг

        F::Log('Application *'.$Call['Run']['Service'].':'.$Call['Run']['Method'].'* finished', LOG_INFO);

        return $Call;
    });