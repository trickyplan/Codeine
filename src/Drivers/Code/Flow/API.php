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
        $Call['API']['Request'] = ['Started' => Started];
        
        $Call = F::Hook('beforeAPIRun', $Call); // Routing here
      
            if (isset($Call['API']['Request']['Service']))
            {
                $Call = F::loadOptions($Call['API']['Request']['Service'], 'API', $Call);
        
                F::startColor('aed581');
                
                if (F::Dot($Call, 'Skip API'))
                    F::Log('API Skipped', LOG_NOTICE);
                else
                {
                    if (F::isCall($Call['API']['Request']))
                    {
                        $Exists = F::Dot($Call,
                                [
                                    'API',
                                    $Call['API']['Request']['Service'],
                                    $Call['API']['Request']['Method']
                                ]) ?? false;
                        
                        $Enabled = F::Dot($Call,
                                [
                                    'API',
                                    $Call['API']['Request']['Service'],
                                    $Call['API']['Request']['Method'],
                                    'Enabled'
                                ]) ?? false;
                        
                        if ($Exists)
                        {
                            if ($Enabled)
                            {
                                $Call = F::Hook('beforeAPIMethodRun', $Call);

                                if ($Call['API']['Response']['Access'])
                                {
                                    F::Log(
                                        'API *' . $Call['API']['Request']['Service'] . ':'
                                        .$Call['API']['Request']['Method'] . '* started',
                                        LOG_INFO,
                                        'Developer'
                                    );
        
                                    $CanOverride = F::Dot($Call, [
                                        'API',
                                        $Call['API']['Request']['Service'],
                                        $Call['API']['Request']['Method'],
                                        'CanOverride'
                                    ]);
                                    
                                    if (empty($CanOverride))
                                        ;
                                    else
                                        foreach ($CanOverride as $Overridden)
                                        {
                                            if (($ParameterFromRequest = F::Dot($Call['Request'], $Overridden)) === null)
                                                ;
                                            else
                                                $Call = F::CopyDot($Call, 'Request.'.$Overridden, 'API.Request.Call.'.$Overridden);
                                        }
                                        
                                    if ($Behaviours = F::Dot($Call, 'API.'.$Call['API']['Request']['Service'].'.'.$Call['API']['Request']['Method'].'.Behaviours') !== null)
                                        $Call['API']['Request']['Call'] = F::Merge($Call['API']['Request']['Call'], $Behaviours);
                                    
                                    $Result = F::Apply($Call['API']['Request']['Service'], $Call['API']['Request']['Method'], $Call, F::Dot($Call, 'API.Request.Call'), ['Return' => 'Output']);
                                    
                                    $Call = F::Hook('afterAPIMethodRun', F::Merge($Call, $Result));
                                    
                                    $Call = F::Dot($Call, 'API.Response.Data', F::Dot($Result, 'Response'));

                                    if ($Status = F::Dot($Result, 'Status'))
                                        $Call = F::Dot($Call, 'API.Response.Status', $Status);
                                    
                                    F::Log('[API] ['.$Call['API']['Request']['Format'].'] [S:'.F::Dot($Result, 'Status.Code').'] '.$Call['API']['Request']['Service'].':'.$Call['API']['Request']['Method'].'('.serialize(F::Dot($Call, 'API.Request.Call')).')', LOG_INFO, 'Access');
                                }
                                else
                                    $Call = F::Dot($Call, 'HTTP.Headers.HTTP/1.1', '403 Forbidden');
                            }
                            else
                            {
                                $Call['API']['Response']['Data'] = 'API Service or Method is disabled';
                                $Call = F::Dot($Call, 'HTTP.Headers.HTTP/1.1', '400 Bad Request');
                            }
                        }
                        else
                        {
                            $Call['API']['Response']['Data'] = 'Unknown API Service or Method';
                            $Call = F::Dot($Call, 'HTTP.Headers.HTTP/1.1', '404 Not Found');
                        }
                    }
                }
        
                F::Log('API *' . $Call['API']['Request']['Service'] . ':' . $Call['API']['Request']['Method'] . '* finished', LOG_INFO, 'Developer');
                F::stopColor();
        
                $Call = F::Merge($Call, $Call['API']['Formats'][$Call['API']['Request']['Format']]);
            }
        
            $Call['API']['Response']['Generated'] = microtime(true);
            $Call['API']['Response']['Time'] = $Call['API']['Response']['Generated'] - $Call['API']['Request']['Started'];
            
            $Call['Output']['Content']['Request'] = $Call['API']['Request']; // Move API Request to Rendering
            $Call['Output']['Content']['Response'] = $Call['API']['Response']; // Move API Response to Rendering

        $Call = F::Hook('afterAPIRun', $Call);
        
        return $Call;
    });
