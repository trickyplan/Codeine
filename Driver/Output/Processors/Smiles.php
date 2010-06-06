<?php

    function F_Smiles_Process ($Data)
    {
        $Smiles = array(
            '=)))'   => 'Laughing',
            ':)))'   => 'Laughing',
            ':-)))'  => 'Laughing',
            '=))'    => 'Grinning',
            ':-))'   => 'Grinning',
            ':))'    => 'Grinning',
            ':-P'    => 'Tongue_Out',
            ':P'    => 'Tongue_Out',
            '=P'    => 'Tongue_Out',
            ';-P'    => 'Tongue_Out_Winking',
            ';P'    => 'Tongue_Out_Winking',
            ':)'     => 'Smiling',
            '=)'     => 'Smiling',
            ':-)'    => 'Smiling',
            ';))'   => 'Grinning_Winking',
            ';)'    => 'Winking',
            ';-))'   => 'Grinning_Winking',
            ';-)'    => 'Winking',
            '=('     => 'Unhappy',
            ':('     => 'Unhappy',
            ':-('    => 'Unhappy',
            '=^_^='  => 'Aww',
            '^_^'    => 'Aww_2',
            ':-*'    => 'Lips_Sealed',
            '-=O'    => 'Gasping',
            'OMG'    => 'Huh',
            'o_O'    => 'Huh',
            'O_o'    => 'Huh_2',
            '<3'     => 'Heart',
            '8>'     => 'Heart',
            '>:O'    => 'Angry',
            ":'("   => 'Crying'
        );
       
       foreach ($Smiles as $Key => $Value)
            if (mb_strpos($Data, $Key)!== false)
                $Data = str_replace(' '.$Key, '<img width="16" height="16" src="/Images/Icons/Mood/'.$Value.'.png" title="<l>Smile:'.$Value.'</l>" alt="<l>Smile:'.$Value.'</l>" /> &nbsp;', $Data);

       return $Data;
    } 
