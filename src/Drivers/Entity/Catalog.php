<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Merge(F::loadOptions('Entity.'.$Call['Entity']), $Call); // FIXME

        $Call = F::Hook('beforeCatalog', $Call);

        $Call['Layouts'][] = ['Scope' => $Call['Entity'],'ID' => 'Catalog'];

        $Elements = F::Run('Entity', 'Read', $Call, ['Fields' => [$Call['Key']]]);

        $Values = [];

        if (count($Elements) > 0)
        {
            foreach ($Elements as $Element)
            {
                $Value = F::Dot($Element, $Call['Key']);
                if (is_array($Value))
                    $Values = array_merge($Values, $Value);
                elseif (is_scalar($Value))
                    $Values[] = $Value;
            }

            $Values = array_count_values($Values);

            arsort($Values);

            $Call['Output']['Content'][] =
                [
                    'Type'    => 'TagCloud',
                    'Value'   => $Values,
                    'Minimal' => $Call['Minimal'],
                    'Entity'  => $Call['Entity'],
                    'Key'     => $Call['Key']
                ];
        }

        $Call = F::Hook('afterCatalog', $Call);

        return $Call;
    });