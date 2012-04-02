<?php

    /* Codeine
     * @author BreathLess
     * @description Create Doctor
     * @package Codeine
     * @version 7.0
     */

    self::setFn('Do', function ($Call)
    {
        $Element = F::Run('Entity', 'Read', array('Entity' => 'User', 'Where' => $Call['ID']));

        if (empty($Element))
            $Call = F::Run('Error.404', 'Page', $Call);
        else
        {
            $Call['Output']['Content'][] = array (
                'Type'  => 'Template',
                'Scope' => 'User',
                'Value' => 'Show.Full',
                'Data'  => $Element[0]
            );

            $Call['Title']       = $Element[0]['Name'].' '.$Element[0]['Surname'];
            $Call['Description'] = $Element[0]['Name'] . ' ' . $Element[0]['Surname'];
            $Call['Keywords']    = preg_split('/\s/', $Element[0]['Name']);

        }

        return $Call;
    });
