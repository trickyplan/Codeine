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
        // В этом месте, практически всегда, происходит роутинг.
        $Call = F::Hook('beforeAPIRun', $Call);
        
        if (preg_match('@/api/(?P<Version>.+)/(?P<Format>.+)/(?P<Service>.+)/(?P<Method>.+)@Ssu', $Call['HTTP']['URL'], $Pockets))
        {
            $Call['API']['Format'] = $Pockets['Format'];
            $Call['API']['Version'] = $Pockets['Version'];
            $Call['API']['Service'] = $Pockets['Service'];
            $Call['API']['Method'] = $Pockets['Method'];
            $Call['Output']['Content']['Request'] = $Call['API'];
            
            $Call = F::loadOptions($Call['API']['Service'], 'API', $Call);

            F::startColor('aed581');
            // Если передан нормальный вызов, совершаем его
        
            if (F::Dot($Call, 'Skip API'))
                F::Log('API Skipped', LOG_NOTICE);
            else
            {
                if (F::isCall($Call['API']))
                {
                    if (F::Dot($Call, 'API.Enabled'))
                    {
                        if (!isset($Call['API']['Method']) or empty($Call['API']['Method']))
                            $Call['API']['Method'] = 'Do';
        
                        list($Call['API']['Service'], $Call['API']['Method'])
                            = [$Call['API']['Service'], $Call['API']['Method']];
        
                        F::Log('API *'.$Call['API']['Service'].':'.$Call['API']['Method'].'* started', LOG_NOTICE, 'All');
                       
                        $Call['Output']['Content']['Response']['Data'] = F::Live($Call['API'], $Call, $Call['Request']);
                    }
                    else
                        $Call['Output']['Content']['Response']['Data'] = 'Unknown API Service or Method';
                }
            }
           
            F::Log('API *'.$Call['API']['Service'].':'.$Call['API']['Method'].'* finished', LOG_NOTICE, 'All');
            F::stopColor();
            
            $Call = F::Merge($Call, $Call['API']['Formats'][$Call['API']['Format']]);
        }
        
        $Call = F::Hook('afterAPIRun', $Call);
        return $Call;
    });
