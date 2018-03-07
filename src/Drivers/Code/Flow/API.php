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
        $Request = ['Started' => Started];
        $Call['API']['Request']['Locale'] = ['Locale' => $Call['Locale']];
        // В этом месте, практически всегда, происходит роутинг.
        $Call = F::Hook('beforeAPIRun', $Call);
        
        if (isset($Call['API']['Request']['Service']))
        {
            $Call = F::loadOptions($Call['API']['Request']['Service'], 'API', $Call);
    
            F::startColor('aed581');
            // Если передан нормальный вызов, совершаем его
    
            if (F::Dot($Call, 'Skip API'))
                F::Log('API Skipped', LOG_NOTICE);
            else
            {
                if (F::isCall($Call['API']['Request']))
                {
                    if (!isset($Call['API']['Request']['Method']) or empty($Call['API']['Request']['Method']))
                        $Call['API']['Request']['Method'] = 'Do';

                    $Enabled = F::Dot($Call, implode('.', [
                            'API',
                            $Call['API']['Request']['Service'],
                            $Call['API']['Request']['Method'],
                            'Enabled'
                        ])) ?? false;
                    if ($Enabled)
                    {
                        if ($Call['API']['Response']['Access'])
                        {
                            F::Log('API *' . $Call['API']['Request']['Service'] . ':' . $Call['API']['Request']['Method'] . '* started', LOG_NOTICE, 'All');

                            $Parameters = F::Dot($Call, [
                                'API',
                                $Call['API']['Request']['Service'],
                                $Call['API']['Request']['Method'],
                                'Parameters'
                            ]);

                            if (empty($Parameters))
                                ;
                            else
                                foreach ($Parameters as $Parameter)
                                    $Request = F::Dot($Request, $Parameter, F::Dot($Call['Request'], $Parameter));

                            if ($Behaviours = F::Dot($Call, 'API.'.$Call['API']['Request']['Service'].'.'.$Call['API']['Request']['Method'].'.Behaviours') !== null)
                                $Request = F::Merge($Request, $Behaviours);
                            
                            $Call['Output']['Content']['Parameters'] = $Parameters;
                            $Call['Output']['Content']['Request'] = $Request;
                    
                            $Call['API']['Response']['Data'] =
                                F::Run($Call['API']['Request']['Service'], $Call['API']['Request']['Method'], $Call, $Request);
                    
                        }
                    } else
                        $Call['API']['Response']['Data'] = 'Unknown API Service or Method';
                }
            }
    
            F::Log('API *' . $Call['API']['Request']['Service'] . ':' . $Call['API']['Request']['Method'] . '* finished', LOG_NOTICE, 'All');
            F::stopColor();
    
            $Call = F::Merge($Call, $Call['API']['Formats'][$Call['API']['Request']['Format']]);
        }
        
        $Call['API']['Response']['Generated'] = microtime(true);
        $Call['API']['Response']['Time'] = $Call['API']['Response']['Generated'] - $Request['Started'];
        $Call['Output']['Content']['Response'] = $Call['API']['Response'];
        
        $Call = F::Hook('afterAPIRun', $Call);
        return $Call;
    });
