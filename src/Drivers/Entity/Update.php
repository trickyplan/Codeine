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

        if (!empty($Call['Where']))
        {
            $Call = F::Hook('beforeEntityUpdate', $Call);

            $Call['Element'] = F::Run('Entity', 'Read', $Call)[0];

            $Call['Locales'][] = $Call['Entity']; // FIXME Hook!

            $Call['Layouts'][] = array(
                        'Scope' => $Call['Entity'],
                        'ID' => 'Main'
                    );// FIXME Hook!

            $Call['Layouts'][] = array(
                        'Scope' => $Call['Entity'],
                        'ID' => 'Update'
                    );// FIXME Hook!

            if ($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                $Call['Element'] = F::Merge($Call['Element'], F::Run('Entity', 'Update', $Call,
                    array (
                          'Data' => $Call['Request']
                    )));

                $Call = F::Hook('afterEntityUpdate', $Call);

                return $Call;
            }

            if (isset($Call['URL']))
                $Call['Output']['Content']['Form']['Action'] = $Call['URL'];

            foreach ($Call['Nodes'] as $Name => $Node)
            {
                if (isset($Node['Widgets']['Update']))
                    $Widget = $Node['Widgets']['Update'];
                elseif (isset($Node['Widgets']['Write']) and (!isset($Node['WriteOnce'])))
                        $Widget = $Node['Widgets']['Write'];
                else
                    $Widget = null;

                if (null !== $Widget)
                {
                    $Value = isset($Node['Default'])? F::Live($Node['Default']): '';

                    if (isset($Call['Element'][$Name]))
                        $Value = $Call['Element'][$Name];

                    $Widget = F::Merge($Widget,
                        array('Name' => $Name,
                              'Entity' => $Call['Entity'],
                              'Value' => $Value));
                }

                if (isset($Call['CustomTemplate']))
                    $Call['Output'][$Name][] = $Widget;
                else
                    $Call['Output']['Form'][$Name] = $Widget;
            }

        }
        else
            $Call = F::Hook('on404', $Call);


        return $Call;
    });