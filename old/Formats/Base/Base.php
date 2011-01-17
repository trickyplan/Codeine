<?php
/*
 * http://www.flickr.com/groups/api/discuss/72157616713786392/
 */

    function F_Base_Encode($Args)
    {
        $Output = '';
        $Alphabet = mb_substr('123456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ', $Args['Base']);

        while ($Args['Input'] >= $Args['Base'])
        {
            $Div = $Args['Input']/$Args['Base'];
            $Mod = ($Args['Input']-($Args['Base']*intval($Div)));
            $Output = $Alphabet[$Mod] . $Output;
            $Args['Input'] = intval($Div);
        }

        if ($Num)
            $Output = $Alphabet[$Num].$Output;

        return $Output;
    }


    function F_Base_Decode($Args) 
    {
        $Alphabet = mb_substr('123456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ', $Args['Base']);
        $Output = 0;
        $Multi = 1;
        
        while (mb_strlen($Args['Input']) > 0)
        {
            $Digit = $Args['Input'][mb_strlen($Args['Input'])-1];
            $Output += $Multi * mb_strpos($Alphabet, $Digit);
            $Multi = $Multi * mb_strlen($Alphabet);
            $Args['Input'] = mb_substr($Args['Input'], 0, -1);
        }

        return $Output;
    }