<?php

function F_SQL1D_Load ($Args)
{
    $Data = array ();

    if (is_array ($Args['Name']))
        $RAW = Data::Read($Args['Point'], '{"Fields":["I","V"],"Where":{"k1 IN (v1)":{"I":'.json_encode($Args['Name']).'}}}');
    else
        $RAW = Data::Read($Args['Point'], '{"Fields":["V"],"Where":{"k1 = v1":{"I":"'.$Args['Name'].'"}}}');

    if (is_array($RAW))
        {
            $Data = array();
            if (!is_array($Args['Name']))
                    foreach($RAW as $Row)
                            $Data[$Row['K']][] = $Row['V'];
            else
                    foreach($RAW as $Row)
                            $Data[$Row['I']][$Row['K']][] = $Row['V'];
        }
        else
            $Data = null;

    return $Data;
}

function F_SQL1D_Save ($Args)
{
   return Data::Update ($Args['Point'], '{"I":"'.$Args['Name'].'","Value": '.json_encode($Args['Data']).'}');
}

function F_SQL1D_Query ($Args)
{
    $Q = $Args['Selector'];
    $IDs = null;
    $Oz = array();

    switch (mb_substr ($Q,0,1))
    {
        case '~':
            // ID
            $IDs = Data::Read($Args['Point'],
                '{"Fields":["I"],"Where":{"k1 = v1":{"I":"'.mb_substr($Q,1).'"}}}');
        break;
    }

    if (is_array ($IDs))
        foreach($IDs as $ID)
            $Oz[$ID['I']] = $ID['I'];
    else
            $Oz = null;

    return $Oz;
}