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

        $Call = F::Hook('beforeCreate', $Call);

        $Call['Locales'][] = $Call['Entity'];

        if (isset($Call['URL']))
            $Call['Output']['Content']['Form']['Action'] = $Call['URL'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $Call['Element'] = F::Run('Entity', 'Create',
                array (
                      'Entity' => $Call['Entity'],
                      'Data' => F::Merge($Call['Data'], $Call['Request'])
                ));

            $Call = F::Hook('afterCreate', $Call);

            return $Call;
        }

        foreach ($Call['Nodes'] as $Name => $Node)
        {
            if (isset($Node['Widgets']['Create']))
                $Call['Output']['Form'][] =
                    F::Merge($Node, F::Merge($Node['Widgets']['Create'],
                        array('Name' => $Name,
                              'Entity' => $Call['Entity'])));

        }

        $Call['Front']['Entity'] = $Call['Entity']; //FIXME

        return $Call;
    });