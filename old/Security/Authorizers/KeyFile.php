<?php

function F_KeyFile_Check($Args)
{
        foreach($Args['Challenge'] as $Key => $Value)
            $Args['Challenge'][$Key] = Code::E('Process/Hash','Get', array('Data'=> file_get_contents($Value)));

        $R = array_diff($Args['True'], $Args['Challenge']);
        
        if (empty($R))
            return true;
        else
            return false;
    }

function F_KeyFile_Input($Keyword)
{
    $FF = file_get_contents($_FILES['KeyFile']['tmp_name']);
    return Code::E('Process/Hash', 'Get', array('Data'=>$FF));
}