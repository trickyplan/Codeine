<?php

    /* Codeine
     * @author BreathLess
     * @description Create Doctor
     * @package Codeine
     * @version 7.0
     */

    self::setFn('Do', function ($Call)
    {
        list($Call[$Call['Entity']]) = F::Run('Entity', 'Read', $Call);
        $Call['Locales'][] = $Call['Entity'];

        if (empty($Call[$Call['Entity']]))
            $Call = F::Run('Error.404', 'Page', $Call);
        else
        {
            $Call['Output']['Content'][] = array (
                'Type'  => 'Template',
                'Scope' => $Call['Entity'],
                'Value' => 'Show/Full',
                'Data' => $Call[$Call['Entity']]
            );
        }

        return $Call;
    });
