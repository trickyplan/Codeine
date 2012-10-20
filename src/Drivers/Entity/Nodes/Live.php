<?php

    /* Codeine
     * @author BreathLess
     * @description Default value support 
     * @package Codeine
     * @version 7.x
     */

    self::setFn('Process', function ($Call)
    {
        foreach ($Call['Nodes'] as $Name => $Node)
        {
            $Run = null;

            switch ($Call['Purpose'])
            {
                case 'Read':
                    if (isset($Node[$Call['Purpose']]))
                    {
                        $Run = $Node[$Call['Purpose']];

                        if (isset($Call['Data']))
                            foreach($Call['Data'] as &$Element)
                            {
                                if (($NewElement = F::Live($Run,
                                    [
                                        'Value' => isset($Element[$Name])? $Element[$Name]: null,
                                        'Entity' => $Call['Entity'],
                                        'Data' => $Element,
                                        'Name' => $Name,
                                        'Node' => $Node,
                                        'Nodes' => $Call['Nodes']])) !== null)
                                {
                                    if (isset($Call['Return Call']) && $Call['Return Call'])
                                        $Element = $NewElement;
                                    else
                                    {
                                        $Element[$Name] = $NewElement;

                                        if (!isset($Call['From Update']) && isset($Node['Read']['Store']) && $Node['Read']['Store'])
                                            F::Run('Entity', 'Update', [
                                                 'Entity' => $Call['Entity'],
                                                 'Where' => $Element['ID'],
                                                 'Data' =>
                                                     [
                                                         $Name => $Element[$Name]
                                                     ]
                                             ]);
                                    }
                                }
                            }
                    }


                break;

                default:
                    if (isset($Node[$Call['Purpose']]))
                        $Run = $Node[$Call['Purpose']];
                    elseif (isset($Node['Write']))
                        $Run = $Node['Write'];

                    if (null !== $Run)
                        if (!isset($Node['User Override']) or empty($Call['Data'][$Name]))
                        {
                            if (isset($Run['Return Call']) && $Run['Return Call'])
                                $Call = F::Live($Run, $Call);
                            else
                                $Call['Data'][$Name] = F::Live($Run, $Call);
                        }

                break;
            }

        }

        return $Call;
    });