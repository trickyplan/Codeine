<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 8.x
     */

    setFn('afterSessionInitialize', function ($Call)
    {
        if (isset($Call['Request']['apikey']))
        {
            $Possible = F::Run('Entity', 'Read',
                            [
                                'Entity' => 'User',
                                'Where' =>
                                [
                                    'APIKey'   => $Call['Request']['apikey']
                                ],
                                'One' => true
                            ]);

            if (empty($Possible))
                F::Log('Invalid API key '.$Call['Request']['apikey'], LOG_WARNING, 'Security');
            else
            {
                F::Log('Authorized as '.$Possible['ID'].' by API key '.$Call['Request']['apikey'], LOG_INFO, 'Security');

                $Call['Session'] = F::Run('Entity', 'Create', $Call,
                [
                    'Entity' => 'Session',
                    'Data' =>
                    [
                        'User' => $Possible['ID'],
                        'API'  => true
                    ],
                    'Where' => $Call['SID'],
                    'One' => true
                ]);
            }
        }

        return $Call;
    });