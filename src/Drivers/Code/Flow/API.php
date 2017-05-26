<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: Фронт контроллер
     * @package Codeine
     * @version 8.x
     * @date 31.08.11
     * @time 1:12
     */

    setFn('Run', function ($Call)
    {
        $Call['API']['Request']['Flow'] = 'API';
        $Call['API']['Response'] = [];
        // В этом месте, практически всегда, происходит роутинг.
        $Call = F::Hook('beforeAPIRun', $Call);
            
            $Call['Output']['Content']['Request'] = $Call['API']['Request'];

            $Call = F::loadOptions($Call['API']['Request']['Service'], 'API', $Call);

            F::startColor('aed581');
            // Если передан нормальный вызов, совершаем его
        
            if (F::Dot($Call, 'Skip API'))
                F::Log('API Skipped', LOG_NOTICE);
            else
            {
                if (F::isCall($Call['API']['Request']))
                {
                    if (F::Dot($Call, 'API.Enabled'))
                    {
                        if ($Call['API']['Response']['Access'])
                        {
                            if (!isset($Call['API']['Request']['Method']) or empty($Call['API']['Request']['Method']))
                                $Call['API']['Request']['Method'] = 'Do';
                        
                            F::Log('API *'.$Call['API']['Request']['Service'].':'.$Call['API']['Request']['Method'].'* started', LOG_NOTICE, 'All');
                           
                            $Call['API']['Response']['Data'] =
                                F::Run($Call['API']['Request']['Service'], $Call['API']['Request']['Method'], $Call, $Call['Request']);
                        }
                    }
                    else
                        $Call['API']['Response']['Data'] = 'Unknown API Service or Method';
                }
            }
           
            F::Log('API *'.$Call['API']['Request']['Service'].':'.$Call['API']['Request']['Method'].'* finished', LOG_NOTICE, 'All');
            F::stopColor();
            
            $Call = F::Merge($Call, $Call['API']['Formats'][$Call['API']['Request']['Format']]);
        $Call['Output']['Content']['Response'] = $Call['API']['Response'];
        
        $Call = F::Hook('afterAPIRun', $Call);
        return $Call;
    });
