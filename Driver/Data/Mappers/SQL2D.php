<?php

function F_SQL2D_Load ($Args)
{
    $Data = array ();

    if (is_array ($Args['Name']))
        $RAW = Data::Read($Args['Point'], '{"Fields":"*","Where":{"k1 IN (v1)":{"I":'.json_encode($Args['Name']).'}}}');
    else
        $RAW = Data::Read($Args['Point'], '{"Fields":"*","Where":{"k1 = v1":{"I":"'.$Args['Name'].'"}}}');

    if (is_array($RAW))
    {
        if (sizeof($RAW)>1)
            foreach($RAW as $Row)
                foreach($Row as $Key => $Value)
                    $Data[$Row['I']][$Key][] = $Value;
        else
            foreach($RAW[0] as $Key => $Value)
                $Data[$Key] = $Value;
    }
    else
        $Data = null;

    return $Data;
}

function F_SQL2D_Save ($Args)
{
    $InsData = array();
    $InsData[$Args['Name']] = array('I' =>$Args['Name']);
    foreach ($Args['Data'] as $Key => $Value)
        $InsData[$Args['Name']][$Key] = $Value[0];

    Data::Create($Args['Point'], $InsData);
}

function F_SQL2D_Query ($Args)
{

    $Q = $Args['Selector'];
    $IDs = null;
    $Oz = array();

    switch (mb_substr ($Q,0,1))
    {
        case '~':
            if (null === $IDs)
                $IDs = Data::Read($Args['Point'],
                    '{"Fields":["I"],"Where":{"k1 = v1":{"I":"'.mb_substr($Q,1).'"}}}');
        break;

        case '=':
            // Key=Value
            list ($Key, $Value) = explode ('=', mb_substr ($Q, 1));
            $IDs = Data::Read($Args['Point'],
                '{"Fields":["I"],"Where":{"k1 = v1":{"'.$Key.'":"'.$Value.'"}}}');
        break;

        case '@':
            // Keyword
            switch (mb_substr($Q, 1))
            {
                case 'Random':
                    $IDs = Data::Read($Args['Point'],
                        '{"Fields":["I"],"Where":{"1=1":{"I":"I"}}}');
                    $IDs = array ($IDs[array_rand($IDs)]);
                break;

                case 'All':
                    $IDs = Data::Read($Args['Point'],
                        '{"Fields":["I"]}');

                break;
            }
        break;

        case '>':
            // Greater
            list ($Key, $Value) = explode ('>', mb_substr ($Q, 1));
            $IDs = Data::Read($Args['Point'], '{"Fields":["I"],"Where":{"k1 > v1":{"'.$Key.'":"'.$Value.'"}}}');
        break;

        case '<':
            // Lesser
            list ($Key, $Value) = explode ('<', mb_substr ($Q, 1));
            $IDs = Data::Read($Args['Point'], '{"Fields":["I"],"Where":{"k1 < v1":{"'.$Key.'":"'.$Value.'"}}}');
        break;

        case '!':
            // Not equal
            list ($Key, $Value) = explode ('!', mb_substr ($Q, 1));
            $IDs = Data::Read($Args['Point'], '{"Fields":["I"],"Where":{"k1 = v1 && k2 != v2":{"K":"'.$Key.'","V":"'.$Value.'"}}}');
        break;

        case '|':
            // Lesser
            list ($Key, $Min, $Max) = explode ('|', mb_substr ($Q, 1));
            $IDs = Data::Read($Args['Point'], '{"Fields":["I"],"Where":{"k1 > v1 && k1 < v2":{"'.$Key.'":"'.$Min.'","V2":"'.$Max.'"}}}');
        break;

        case '+':
            // Statistics by key
            switch (mb_substr($Q, 1, 4))
            {
                case 'max:':
                    mb_substr($Q, 5);
                break;

                case 'min:':
                    mb_substr($Q, 5);
                break;
            }

        break;
    }

    if (null === $IDs)
            $IDs = Data::Read($Args['Point'],
            '{"Fields":["I"],"Where":{"k1 = v1":{"I":"'.$Q.'"}}}');

    if (is_array ($IDs))
        foreach($IDs as $ID)
            $Oz[$ID['I']] = $ID['I'];
    else
            $Oz = null;
            
    return $Oz;
}

function F_SQL2D_Sort ($Args)
{
    $DL = '{"Fields":["I"],"Sort":{"Key":"'.$Args['Key'].'","Direction":"'.$Args['Direction'].'"},"Where":{"k1 IN (v1)":{"I":'.json_encode($Args['Name']).'}}}';

    $RAW = Data::Read($Args['Point'], $DL);

    if (is_array($RAW))
        {
            $Data = array();
            foreach($RAW as $Row)
                $Data[] = $Row['I'];
        }
        else
            $Data = null;

    return $Data;
}

function F_SQL2D_Variants ($Args)
{
    $DL = '{"Unique":true,"Fields":["'.$Args['Key'].'"]}';

    $RAW = Data::Read($Args['Point'], $DL);

    if ($RAW)
        {
            $Data = array();
            foreach($RAW as $Row)
                $Data[$Row[$Args['Key']]] = $Row[$Args['Key']];
        }
        else
            $Data = null;

    return array_unique ($Data);
}