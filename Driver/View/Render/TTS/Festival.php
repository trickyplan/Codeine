<?php

    /* OSWA Codeine
     * @author BreathLess
     * @type Codeine Driver
     * @description: Voice Renderer
     * @package Codeine
     * @subpackage Drivers
     * @version 5.0
     * @date 16.11.10
     * @time 3:38
     */

    self::Fn('Do', function ($Call)
    {
        if (is_array($Call['Input']['Items']))
            foreach ($Call['Input']['Items'] as $ID => $Item)
            {
                $Output[$ID] = Code::Run(
                    array('N'=>'View.UI.TTS.'.$Item['UI'],
                         'F' => 'Make',
                         'D' => $Item ['UI'],
                         'Item'=> Core::Any($Item))
                );
            }

        header ('Content-type: audio/x-wav;');
        header('Content-Disposition: attachment; filename=codeine.wav');
        exec('echo "'.implode(' ',$Output).'" | text2wave -o '.Root.'Temp/f.wav');
        readfile(Root.'Temp/f.wav');die();
    });
