<?php

    /* Codeine
     * @author bergstein@trickyplan.com
     * @description Create Doctor
     * @package Codeine
     * @version 7.0
     */

    setFn('Do', function ($Call)
    {
        $Element = F::Run('Entity', 'Read', ['Entity' => 'User', 'Where' => $Call['ID']]);

        if (empty($Element))
            $Call = F::Hook('NotFound', $Call);
        else
        {
            $Call['Output']['Content'][] = array (
                'Type'  => 'Template',
                'Scope' => 'User',
                'Value' => 'Show.Full',
                'Data'  => $Element[0]
            );

            $Call['Page']['Title']       = $Element[0]['Name'].' '.$Element[0]['Surname'];
            $Call['Page']['Description'] = $Element[0]['Name'] . ' ' . $Element[0]['Surname'];
            $Call['Page']['Keywords']    = preg_split('/\s/', $Element[0]['Name']);

        }

        return $Call;
    });
