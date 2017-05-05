<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('beforeOperation', function ($Call)
    {
        if (isset($Call['Where']))
        {
            $Call['Where'] = F::Live($Call['Where'], $Call);

            // Если в Where скалярная переменная - это ID.

            if (is_scalar($Call['Where']))
            {
                if (strpos($Call['Where'], ',') !== false)
                    $Call['Where'] = ['ID' => explode(',', $Call['Where'])];
                else
                    $Call['Where'] = ['ID' => $Call['Where']];
            }

            $Where = [];

            if (isset($Call['Nodes']))
                foreach ($Call['Nodes'] as $Name => $Node)
                {
                    if (($Value = F::Dot($Call['Where'], $Name)) !== null)
                    {
                        if (isset($Node['Type']))
                        {
                            if (is_array($Value))
                            {
                                foreach ($Value as $Relation => $cValue)
                                    if (is_array($cValue))
                                        foreach($cValue as $ccKey => $ccValue)
                                            $Value[$Relation][$ccKey] =
                                                F::Run('Data.Type.'.$Node['Type'], 'Where',
                                                    [
                                                        'Name' => $Name,
                                                        'Node' => $Node,
                                                        'Value' => $ccValue
                                                    ]);
                                    else
                                        $Value[$Relation] = F::Run('Data.Type.'.$Node['Type'], 'Where',
                                            [
                                                'Name' => $Name,
                                                'Node' => $Node,
                                                'Value' => $cValue
                                            ]);
                                
                                // FIXME Нативные массивы?
                                if (isset($Node['Array Like']) && $Node['Array Like'])
                                    $Value = ['$elemMatch' => $Value];

                                // FIXME: Mongo, fuck you!
                            }
                            else
                            {
                                $Value = F::Run('Data.Type.'.$Node['Type'], 'Where',
                                    [
                                        'Name' => $Name,
                                        'Node' => $Node,
                                        'Value' => $Value
                                    ]);
                            }
                        }

                        if (null === $Value)
                            F::Log('Empty '.$Name.' in where', LOG_INFO);
                        else
                            $Where[$Name] = $Value;
                    }
                }

            if (empty($Where))
                $Call['Where'] = null;
            else
                $Call['Where'] = $Where;
        }
        else
        {
            if (isset($Call['No Where']))
                ;
            else
            {
                F::Log('No where (bad idea). Entity *'.$Call['Entity'].'*', LOG_ERR, 'Administrator');
                return null;
            }
            
        }

        return $Call;
    });
