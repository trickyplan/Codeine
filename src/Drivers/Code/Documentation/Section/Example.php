<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 6.0
     */

    self::setFn ('Do', function ($Call)
    {
        $Widgets = array(
        );

        foreach ($Call['Example'] as $ExampleName => $Example)
        {
            $exCall = array();
            foreach ($Example['Call'] as $Key => $Value)
                $exCall [] = '\''.$Key.'\' => \''.$Value.'\'';

            $Widgets[] =
                array(
                    'Place'     => 'Content',
                    'Type'      => 'Heading',
                    'Level'     => 5,
                    'Value'     => $ExampleName
                );

            $Widgets[] = array(
                'Place'     => 'Content',
                'Type'      => 'Example',
                'Value'     =>
                '<?php
echo F::Run(\''.$Call['Service'].'\', \''.$Call['Method'].'\',
    array(
    '.implode(",\n\t", $exCall).');
?>',
                'Output' => $Example['Result']
            );

        }

        return $Widgets;
    });