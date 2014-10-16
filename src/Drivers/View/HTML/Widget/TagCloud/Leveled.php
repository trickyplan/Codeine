<?php

    /* Codeine
     * @author BreathLess
     * @description  
     * @package Codeine
     * @version 7.x
     */

    setFn('Make', function ($Call)
    {
        $Output = '';

        for ($LevelNumber = 1; $LevelNumber<=$Call['Tag Cloud Levels']; $LevelNumber++)
        {
            $Level = array_slice($Call['Value'], (($LevelNumber-1)*4), ($LevelNumber*4));

            $LevelString = '';

            foreach ($Level as $Value => $Count)
            {
                if ($Count > $Call['Minimal'])
                    $LevelString .= F::Run('View', 'Load',
                        [
                            'Scope' => $Call['View']['HTML']['Widget Set'].'/Widgets',
                            'ID' => 'TagCloud/Leveled/Tags',
                            'Data' =>
                                [
                                    'Level' => $LevelNumber,
                                    'Count' => $Count,
                                    'Value' => $Value
                                ]
                        ]);
            }

            $Output .= F::Run('View', 'Load',
                        [
                            'Scope' => $Call['View']['HTML']['Widget Set'].'/Widgets',
                            'ID' => 'TagCloud/Leveled',
                            'Data' =>
                                [
                                    'Level' => $LevelNumber,
                                    'Value' => $LevelString
                                ]
                        ]);
        }


        $Call['Value'] = $Output;

        return $Call;
    });