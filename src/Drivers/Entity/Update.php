<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.2
     */

    self::setFn('Do', function ($Call)
    {
        $Call = F::Merge( F::loadOptions('Entity.'.$Call['Entity']), $Call);

        $Call['Where'] = F::Live($Call['Where']);

        $Call['Element'] = F::Run('Entity', 'Read', $Call);

        $Call = F::Hook('beforeEntityUpdate', $Call);

        $Call['Locales'][] = $Call['Entity']; // FIXME Hook!

        $Call['Layouts'][] = array(
                    'Scope' => $Call['Entity'],
                    'ID' => 'Main'
                );// FIXME Hook!

        $Call['Layouts'][] = array(
                    'Scope' => $Call['Entity'],
                    'ID' => 'Update'
                );// FIXME Hook!

        $Call['Element'] = $Call['Element'][0];

        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $Call['Element'] = F::Merge($Call['Element'], F::Run('Entity', 'Update', $Call,
                array (
                      'Data' => F::Merge($Call['Data'], $Call['Request'])
                )));

            $Call = F::Hook('afterEntityUpdate', $Call);

            return $Call;
        }

        if (isset($Call['URL']))
            $Call['Output']['Content']['Form']['Action'] = $Call['URL'];

        foreach ($Call['Nodes'] as $Name => $Node)
        {
            if (isset($Node['Widgets']['Write']) and (!isset($Node['WriteOnce'])))
                $Call['Output']['Form'][] =
                    F::Merge($Node['Widgets']['Write'],
                        array('Name' => $Name,
                              'Entity' => $Call['Entity'],
                              'Value' => $Call['Element'][$Name]));
        }

        return $Call;
    });