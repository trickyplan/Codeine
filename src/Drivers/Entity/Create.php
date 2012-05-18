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

        $Call['Layouts'][] = array(
                    'Scope' => $Call['Entity'],
                    'ID' => 'Create'
                );

        if (isset($Call['URL']))
            $Call['Output']['Content']['Form']['Action'] = $Call['URL'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $Call['Element'] = F::Run('Entity', 'Create',
                array (
                      'Entity' => $Call['Entity'],
                      'Data' => isset($Call['Data'])? F::Merge($Call['Data'], $Call['Request']): $Call['Request']
                ));

            $Call = F::Hook('afterCreate', $Call);

            return $Call;
        }

        foreach ($Call['Nodes'] as $Name => $Node)
        {
            if (isset($Node['Widgets']['Create']))
                $Call['Output']['Form'][] = F::Merge($Node['Widgets']['Create'], array('Name' => $Name, 'Entity' => $Call['Entity']));
            else
                $Call['Output']['Form'][] = F::Merge(F::Run('Entity.Nodes.Type.'.$Node['Type'], 'Widget', $Call, array ('Purpose' => 'Create', 'Node' => $Node)),
                    array('Entity' => $Call['Entity'], 'Name' => $Name));
        }

        $Call['Front']['Entity'] = $Call['Entity']; //FIXME

        return $Call;
    });