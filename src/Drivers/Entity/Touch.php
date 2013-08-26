<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call = F::Run('Entity', 'Load', $Call);

        $Call = F::Hook('beforeTouch', $Call);

        $Data = F::Run('Entity', 'Read', $Call);

        if (is_array($Data))
        {
            foreach ($Data as $Element)
            {
                $New = F::Run('Entity', 'Update', $Call,
                    [
                        'One' => true,
                        'Where'=> $Element['ID'],
                        'Data' => $Element
                    ])['Data'];

                $Call['Output']['Content'][] =
                [
                    'Type' => 'Block',
                    'Value' => 'Element '.$New['ID'].' touched'
                ];
            }
        }

        $Call = F::Hook('afterTouch', $Call);

        return $Call;
    });