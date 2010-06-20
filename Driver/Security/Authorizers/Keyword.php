<?php

function F_Keyword_Check($Args)
{
        foreach($Args['Challenge'] as $Key => $Value)
            $Args['Challenge'][$Key] = Code::E('Process/Hash','Get', $Value);

        $R = array_diff($Args['True'], $Args['Challenge']);
        if (empty($R))
            return true;
        else
            return false;
    }

function F_Keyword_Input($Keyword)
{
    return Code::E('Process/Hash', 'Get', $Keyword);
}