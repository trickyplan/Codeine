<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description: 
     * @package Codeine
     * @version 8.x
     * @date 31.08.11
     * @time 6:17
     */

    setFn('beforeRoute', function ($Call)
    {
        if (isset($Call['Routing']['Extension']) && is_string($Call['Run']))
        {
            foreach ($Call['Routing']['Extension'] as $Extension => $ExtensionMixin)
            {
                $Pattern = '/\.'.$Extension.'$/';

                if (preg_match($Pattern, $Call['Run']))
                {
                    $Call['Extension Call'] = $ExtensionMixin;
                    $Call['Run'] = preg_replace($Pattern, '', $Call['Run']);
                    F::Log('.'.$Extension.' extension detected', LOG_INFO);
                    break;
                }
            }
        }

        return $Call;
    });

    setFn('afterRoute', function ($Call)
    {
        if (isset($Call['Extension Call']))
            $Call['Run'] = F::Merge($Call['Run'], $Call['Extension Call']);
        return $Call;
    });