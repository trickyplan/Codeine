<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.4.5
     */

    self::setFn('Do', function ($Call)
    {
        $Call['Locales'][] = 'Code/Documentation:ByContract';

        $Call['Contract'] = F::loadOptions('Security.Hash.MD5')['Get'];
        $Call['Contract']['Service'] = 'Security.Hash.MD5';
        $Call['Contract']['Method'] = 'Get';

        foreach ($Call['Contract']['Example'] as $Example)
        {
            $Args = array();
            foreach ($Example['Call'] as $Key => $Value)
                $Args[] = "'".$Key."' => '".$Value."'";

            $Call['Contract']['Code'] =
                "F::Run('".$Call['Contract']['Service']."', '".$Call['Contract']['Method']."', array(".implode(',', $Args).")";

        }

        $Call['Output']['Content'][] =
            array(
                'Type' => 'Template',
                'Scope' => 'Code/Documentation',
                'ID' => 'Method',
                'Data' => $Call['Contract']
            );

        return $Call;
    });