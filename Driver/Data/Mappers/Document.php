<?php

function F_Document_Load ($Args)
{
    if (is_array($Args['Name']))
        foreach($Args['Name'] as $Name)
            $RAW[$Name] = Data::Read($Args['Point'], array('I'=> $Name));
        else
            $RAW = Data::Read($Args['Point'], array('I' => $Args['Name']));

    return $RAW;
}

function F_Document_Save ($Args)
{
    $Args['Data']['I'] = $Args['Name'];
    return Data::Update ($Args['Point'], json_encode($Args['Data']));
}

function F_Document_Query ($Args)
{
    $Q = $Args['Selector'];
    $Oz = array();
    $IDs = null;

    switch (mb_substr ($Q,0,1))
    {
        case '~':
            // ID
            $IDs = Data::Read($Args['Point'], '{"I":"'.mb_substr ($Q, 1).'"}');
        break;
    }

    if (is_array ($IDs))
        foreach($IDs as $ID)
            $Oz[$ID['I']] = $ID['I'];
    else
        $Oz = null;

    return $Oz;
}

function F_Document_Variants ($Args)
{
    return null;
}