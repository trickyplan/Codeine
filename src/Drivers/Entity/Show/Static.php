<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Create Doctor
     * @package Codeine
     * @version 7.0
     */

    setFn('Before', function ($Call)
    {
        $Call = F::Hook('beforeShowBefore', $Call);

            $Call['Where'] = F::Live($Call['Where']);

            $Call['Data'] = F::Run('Entity', 'Read', $Call, ['One' => true, 'Limit' => ['From' => 0, 'To' => 1]]);

        $Call = F::Hook('afterShowBefore', $Call);
        return $Call;
    });

    setFn('Do', function ($Call)
    {
        $Call = F::Hook('beforeShow', $Call);
        $Call = F::Hook('beforeShowDo', $Call);

        /*foreach ($Call['Data'] as $Node => $Value)
        {
            if (isset($Call['Nodes'][$Node]['Widgets']))
                ;
            else
                unset ($Call['Data'][$Node]);
        }*/

        if (isset($Call['Data']['Redirect']) && !empty($Call['Data']['Redirect']))
            $Call = F::Apply('System.Interface.HTTP','Redirect', $Call, ['Redirect' => $Call['Data']['Redirect']]);
        else
        {
            $Call['Layouts'][] = ['Scope' => $Call['Entity'],'ID' => 'Show','Context' => $Call['Context']];

            $Call['Scope'] = isset($Call['Scope'])? $Call['Entity'].'/'.$Call['Scope'] : $Call['Entity'];

            if (empty($Call['Data']))
                $Call = F::Hook('onShowNotFound', $Call);
            else
            {
                $Call['Output']['Content'][] = array (
                    'Type'  => 'Template',
                    'Scope' => $Call['Scope'],
                    'ID' => 'Show/'.(isset($Call['Template'])? $Call['Template']: 'Full'),
                    'Data' => $Call['Data']
                );

                $Call = F::Hook('afterShow', $Call);
            }
        }

/*        if (isset($Call['Data']['Modified']))
            $Call['HTTP']['Headers']['Last-Modified:'] = date(DATE_RFC2822, $Call['Data']['Modified']);
        else
            $Call['HTTP']['Headers']['Last-Modified:'] = date(DATE_RFC2822, $Call['Data']['Created']);*/

        F::Log(function () use ($Call) {return $Call['Data'];} , LOG_DEBUG);

        return $Call;
    });
