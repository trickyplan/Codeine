<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Hook('beforeFilterDo', $Call);
        if (isset($Call['Request']['Filter']))
        {
            if (isset($Call['Data']))
                $Call['Data'] = F::Merge($Call['Request']['Filter'], $Call['Data']);
            else
                $Call['Data'] = $Call['Request']['Filter'];
        }
        else
            $Call['Data'] = [];


        $Call['Scope'] = isset($Call['Scope'])? $Call['Scope']: $Call['Scope'] = $Call['Entity'];

        $Call['Layouts'][] = ['Scope' => $Call['Entity'],'ID' => 'Filter','Context' => $Call['Context']];

        $Call['Layouts'][] =
            [
                'Scope' => $Call['Scope'],
                'ID' => isset($Call['Custom Layouts']['Filter'])?  $Call['Custom Layouts']['Filter']: 'Filter',
                'Context' => $Call['Context']
            ];

        $Call['Output']['Content']['Form'] = [
                'Type' => 'Form',
                'Action' => isset($Call['Action'])? $Call['Action']: ''
        ];

        // Для каждой ноды в модели
        $ic = 0;
        foreach ($Call['Nodes'] as $Name => $Node)
        {
            $Widget = null;
            // Если виджеты вообще определены, и фильтрация разрешена

            if (isset($Node['Widgets']) && isset($Node['Filterable']) && $Node['Filterable'])
            {
                // Определяем, какие именно используем
                if (isset($Node['Widgets'][$Call['Purpose']])) // Для нашего случая
                    $Widget = $Node['Widgets'][$Call['Purpose']];
                elseif (isset($Node['Widgets']['Write'])) // Для записи как таковой
                    $Widget = $Node['Widgets']['Write'];

                if (null !== $Widget)
                {
                    $Widget['Entity'] = $Call['Entity'];
                    $Widget['Label'] = $Call['Entity'].'.Entity:'.$Name;
                    $Widget['Node'] = $Name;

                    $Widget['Name']   = 'Filter';

                    if (strpos($Name, '.') !== false)
                    {
                        $Slices = explode('.', $Name);

                        foreach ($Slices as $Slice)
                            $Widget['Name'].= '['.$Slice.']';
                    }
                    else
                        $Widget['Name'] .= '['.$Name.']';

                    $Widget['Key'] = $Name;

                    $Widget['ID'] = strtr($Name, '.','_');
                    $Widget['Context'] = $Call['Context'];

                    $Widget = F::Merge($Node, $Widget);

                    if (isset($Call['Data']))
                        $Widget['Data'] = $Call['Data'];

                    if (isset($Widget['Options']))
                        $Widget['Options'] = F::Live($Widget['Options'], $Call);
                    else
                        $Widget['Options'] = [];

/*                    if($ic == 0)
                        $Widget['Autofocus'] = true;*/

                    // Если есть значение, добавляем
                    if (isset($Call['Data']))
                        if (($Widget['Value'] =  F::Dot($Call['Data'], $Name)) === null)
                            if (isset($Node['Default']))
                                $Widget['Value'] = F::Live($Node['Default']);

                    // Упростить

                    if (isset($Widget['Value']))
                        $Widget['Value'] = F::Live($Widget['Value']);
                    else
                        $Widget['Value'] = null;

                    // Помещаем виджет в поток
                    $ic++;

                    $Call = F::Apply('Entity.Form.Layout.'.$Call['FormLayout'], 'Add', $Call,
                        [
                            'IC' => $ic,
                            'Name' => $Name,
                            'Widget' => $Widget
                        ]);

                    $Call['Widget'] = null;
                }
            }
        }

        // Вывести
        $Call = F::Hook('afterFilterDo', $Call);

        return $Call;
    });