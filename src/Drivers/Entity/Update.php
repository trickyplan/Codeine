<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Do', function ($Call)
    {
        $Call = F::Merge($Call, F::loadOptions('Entity.'.$Call['Entity']));
        $Call['Where'] = F::Live($Call['Where']);

        $Call['Element'] = F::Run('Entity', 'Read', $Call);

        $Call = F::Hook('beforeUpdate', $Call);

        $Call['Locales'][] = $Call['Entity'];



        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {

            $Call['Element'] = F::Merge($Call['Element'][0], F::Run('Entity', 'Update', $Call,
                array (
                      'Data' => F::Merge($Call['Data'], $Call['Request'])
                )));

            $Call = F::Hook('afterUpdate', $Call);

            return $Call;
        }

        if (isset($Call['URL']))
            $Call['Output']['Content']['Form']['Action'] = $Call['URL'];

        foreach ($Call['Nodes'] as $Name => $Node)
        {
            if (!isset($Node['Widgets']['Update']))
            {
                    if (isset($Node['Widgets']['Create']))
                        $Node['Widgets']['Update'] = $Node['Widgets']['Create'];
            }

            $Call['Output']['Form'][] =
                F::Merge($Node['Widgets']['Update'],
                     array('Name' => $Name,
                           'Entity' => $Call['Entity'],
                           'Value' => isset($Node['Widgets']['Update']['Value'])? $Node['Widgets']['Update']['Value']: F::Live($Call['Element'][0][$Name]),
                           'Selected' => F::Live($Call['Element'][0][$Name])));

        }

        $Call['Front'] = $Call['Element'][0]; //FIXME

        return $Call;
    });