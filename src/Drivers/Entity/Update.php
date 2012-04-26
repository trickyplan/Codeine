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

        $Call['Element'] = F::Run('Entity', 'Read', $Call);

        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $Call['Element'] = F::Run('Entity', 'Update', $Call,
                array (
                      'Data' => F::Merge($Call['Data'], $Call['Request'])
                ));

            // TODO SLUG
            d(__FILE__, __LINE__, $Call);
            $Call['Value'] = '/'.strtolower($Call['Entity']).'/'.$Call['Element']['Slug']; // FIXME Reverse routing #243

            $Call = F::Run('Code.Flow.Hook', 'Run', $Call, array ('On'=> 'afterUpdate'));

            return $Call;
        }

        $Call['Output']['Content']['Form']['Action'] = $Call['URL'];

        foreach ($Call['Nodes'] as $Name => $Node)
        {
            if (isset($Node['Widgets']['Create']))
                $Call['Output']['Form'][] =
                    F::Merge($Node['Widgets']['Create'],
                         array('Name' => $Name,
                               'Entity' => $Call['Entity'],
                               'Value' => isset($Node['Widgets']['Create']['Value'])? $Node['Widgets']['Create']['Value']: F::Live($Call['Element'][0][$Name]),
                               'Selected' => F::Live($Call['Element'][0][$Name])));

        }

        $Call['Front'] = $Call['Element'][0]; //FIXME

        return $Call;
    });