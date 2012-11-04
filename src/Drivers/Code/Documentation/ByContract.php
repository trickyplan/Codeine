<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Do', function ($Call)
    {
        $Call['Locales'][] = 'Code/Documentation:ByContract';

        $Call['Contract'] = F::loadOptions($Call['Doc']['Service'], $Call['Doc']['Method']);

        foreach ($Call['Contract']['Example'] as $Name => $Example)
        {
            $Args = array();
            $Call['Contract']['Code'][] = '<h4>'.$Name.'</h4>'.
highlight_string("<?php
    F::Run('".$Call['Doc']['Service']."', '".$Call['Doc']['Method']."',
".var_export($Example['Call'], true).');
?>
    '.$Example['Result'], true);

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