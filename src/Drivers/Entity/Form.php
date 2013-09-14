<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Generate', function ($Call)
    {
        $IC = 0;

        if (!isset($Call['Data']))
            $Call['Data'] = [];

        // Для каждой ноды в модели
        foreach ($Call['Nodes'] as $Name => $Node)
        {
            $IC++;
            $Widget = null;

            // Режим «только обязательные поля»

            if (isset($Call['OnlyRequired']) &&
                $Call['OnlyRequired'] == true && (!isset($Node['Required']) or !$Node['Required']))
                continue;

            // Если виджеты вообще определены
            if (isset($Node['Widgets']))
            {
                // Определяем, какие именно используем
                if (isset($Node['Widgets'][$Call['Purpose']])) // Для нашего случая
                    $Widget = $Node['Widgets'][$Call['Purpose']];
                elseif (isset($Node['Widgets']['Write'])) // Для записи как таковой
                    $Widget = $Node['Widgets']['Write'];

                if (isset($Node['Scope']) && !in_array($Call['Tag'], (array) $Node['Scope']))
                    continue;

                if($Call['Purpose'] == 'Create' && isset($Call['Data'][$Name]))
                    $Widget['Type'] = 'Form.Static';

                if (null !== $Widget)
                {
                    // Передаём имя сущности

                    $Widget = F::Merge($Node, $Widget);

                    $Widget['Entity'] = $Call['Entity'];
                    $Widget['Node'] = $Name;
                    $Widget['Name'] = 'Data'.'['.$Call['IX'].']';
                    $Widget['Key'] = $Name;
                    $Widget['ID'] = strtr($Name, '.','_');
                    $Widget['Context'] = $Call['Context'];

                    if($IC == 0)
                        $Widget['Autofocus'] = true;

                    if (isset($Node['Options']))
                        $Widget['Options'] = F::Live($Node['Options']);
                    else
                        $Widget['Options'] = [];

                    if (isset($Node['Localized']) && $Node['Localized'])
                        $Widget['Label']  = $Call['Entity'].'.Entity:'.$Name.'.Label';
                    else
                        $Widget['Label']  = $Call['Entity'].'.Entity:'.$Name;

                    if (strpos($Name, '.') !== false)
                    {
                        $Slices = explode('.', $Name);

                        foreach ($Slices as $Slice)
                            $Widget['Name'].= '['.$Slice.']';
                    }
                    else
                        $Widget['Name'] .= '['.$Name.']';

                    if (isset($Call['Data']))
                        $Widget['Data'] = $Call['Data'];

                    // Если есть значение, добавляем
                    if (($Widget['Value'] = F::Dot($Call['Data'], $Name)) === null)
                    {
                        if (isset($Node['Default']))
                            $Widget['Value'] = F::Live($Node['Default']);
                        else
                            $Widget['Value'] = null;
                    }
                    else
                        $Widget['Value'] = F::Live($Widget['Value']);

                    // Упростить

                    if (!isset($Widget['Weight']))
                        $Widget['Weight'] = count($Call['Nodes'])-$IC; // Magic

                    $Widget['Weight'] = sprintf('X%02dW%04d',$Call['IX'], $Widget['Weight']);

                    //$Widget['Label'] .=$Widget['Weight'];
                    // Помещаем виджет в поток

                    $Call = F::Run('Entity.Form.Layout.'.$Call['FormLayout'], 'Add', $Call,
                        [
                            'Name' => $Name,
                            'Widget' => $Widget
                        ]);

                    $Call['Widget'] = null;
                }
            }
        }

        if (isset($Call['Output']['Form']))
            $Call['Output']['Form'] = F::Sort($Call['Output']['Form'], 'Weight', SORT_DESC);

        return $Call;
    });