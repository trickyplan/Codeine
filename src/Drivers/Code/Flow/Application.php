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
        if (isset($Call['Run']['Service']))
        {
            if (isset($Call['Run']['Method']))
            {
                list($Call['Service'], $Call['Method']) = array ($Call['Run']['Service'], $Call['Run']['Method']);

                F::Log('Application *'.$Call['Service'].':'.$Call['Method'].'* started', LOG_NOTICE);
        
                $Call = F::Hook('beforeApplicationRun', $Call); // В этом месте, практически всегда, происходит роутинг.
        
                    $Call = F::Live($Call['Run'], $Call);
        
                $Call = F::Hook('afterApplicationRun', $Call); // А здесь - рендеринг
        
/*                F::Log('Application *'.$Call['Run']['Service'].':'.$Call['Run']['Method'].'* finished', LOG_INFO);*/
        
                if (is_array($Call))
                    $Call['Context'] = '';
            }
            else
                F::Log('Method not specified, skip', LOG_WARNING);
        }
        else
            F::Log('Service not specified, skip', LOG_WARNING);
        
        return $Call;
    });
