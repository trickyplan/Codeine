<?php

function SQL3D($RAW, $Name = null)
{
    if ($RAW !== null)
        {
            $Data = array();
            $SZ = sizeof($RAW);

            if (!is_array($Name))
                for($IC=0; $IC<$SZ; $IC++)
                    $Data[$RAW[$IC]['K']][] = $RAW[$IC]['V'];
            else
                for($IC=0; $IC<$SZ; $IC++)
                    $Data[$RAW[$IC]['I']][$RAW[$IC]['K']][] = $RAW[$IC]['V'];
        }
        else
            $Data = null;
        
    return $Data;
}

function F_SQL3D_Load ($Args)
{
    if (empty($Args['Name'])) 
        return null;

    if (!is_array($Args['Name']))
        $DL = array('Fields' => array('I','K','V'), 'Where'=>array('k1 = v1'=>array('I' => $Args['Name'])));
    else
    {
        if ($Args['Name'][0] == '@All')
            $DL = array('Fields' => array('I','K','V'));
        elseif (sizeof($Args['Name'])>1)
            $DL = array('Fields' => array('I','K','V'), 'Where'=>array('k1 IN (v1)'=>array('I'=> $Args['Name'])));
        else
            $DL = array('Fields' => array('I','K','V'), 'Where'=>array('k1 = v1'=>array('I' => $Args['Name'])));
    }
  
    $Data = SQL3D(Data::Read($Args['Point'], $DL), $Args['Name']);

    if (isset ($Args['Key']))
        return $Data[$Args['Key']];
    else
        return $Data;
}
function F_SQL3D_Erase ($Args)
{
    return Data::Delete($Args['Point'],
                        array('k1 = v1' =>
                           array('I'=>$Args['Name'])));
}

function F_SQL3D_Save ($Args)
{
        $DML = &$Args['DML'];
        
        if (isset($DML['Del']) and !empty($DML['Del']))
        {
            foreach ($DML['Del'] as $Row)
                if (null !== $Row['Old'])
                    Data::Delete($Args['Point'],
                        array('k1 = v1 && k2 = v2 && k3 = v3' =>
                           array('I'=>$Args['Name'], 'K'=>$Row['Key'], 'V'=>$Row['Old'])));
                else
                    Data::Delete($Args['Point'],
                        array('k1 = v1 && k2 = v2' =>
                           array('I'=>$Args['Name'], 'K'=>$Row['Key'])));
        }

        if (isset($DML['Add']) and !empty($DML['Add']))
        {
            foreach ($DML['Add'] as $Row)
                $InsData[] = array('I'=>$Args['Name'], 'K'=>$Row['Key'], 'V'=> $Row['Value']);
                
            Data::Create($Args['Point'], $InsData);
        }
        
        if (isset($DML['Set']) and !empty($DML['Set']))
        {
            foreach ($DML['Set'] as $Row)
                if (null !== $Row['Old'])
                    Data::Update($Args['Point'],
                    array('Data'=>array('I'=>$Args['Name'], 'K'=>$Row['Key'], 'V'=>$Row['Value']),
                          'Where'=>array('k1 = v1 && k2 = v2 && k3 = v3' =>
                           array('I'=>$Args['Name'], 'K'=>$Row['Key'], 'V'=>$Row['Old']))));
                else
                    Data::Update($Args['Point'],
                    array('Data'=>array('I'=>$Args['Name'], 'K'=>$Row['Key'], 'V'=>$Row['Value']),
                          'Where'=>array('k1 = v1 && k2 = v2' =>
                           array('I'=>$Args['Name'], 'K'=>$Row['Key']))));
        }
}

function F_SQL3D_Query ($Args)
{

    $Q = $Args['Selector'];
    $IDs = null;
    $Oz = array();

    $Fields = '["I"]';

    switch (mb_substr ($Q,0,1))
    {
        case '~':
            $IDs = Data::Read($Args['Point'],
                    '{"Fields":'.$Fields.',"Where":{"k1 = v1 && k2 = v2":{"K":"Handle","V":"'.mb_substr($Q,1).'"}}}');

            if ($IDs === null)
                $IDs = Data::Read($Args['Point'],
                    '{"Fields":'.$Fields.',"Where":{"k1 = v1":{"I":'.json_encode(mb_substr($Q,1)).'}}}');
        break;

        case '=':
            // Key=Value
            list ($Key, $Value) = explode ('=', mb_substr ($Q, 1));
            if (mb_substr($Value,0,1) == '(')
            {
                $IDs = Data::Read($Args['Point'],
                    '{"Fields":'.$Fields.',"Where":{"k1 = v1 && k2 IN (v2)":{"K":"'.$Key.'","V":'.
                    json_encode(
                        explode(',',
                            mb_substr($Value, 1, mb_strlen($Value)-2)
                               )
                        ).'}}}');
            }
            else
                $IDs = Data::Read($Args['Point'],
                    '{"Fields":'.$Fields.',"Where":{"k1 = v1 && k2 = v2":{"K":"'.$Key.'","V":"'.$Value.'"}}}');
        break;

        case '*':
            // *Key*Value
            list ($Key, $Value) = explode ('*', mb_substr ($Q, 1));
            
            $IDs = Data::Read($Args['Point'],
                '{"Fields":'.$Fields.',"Where":{"k1 = v1 && k2 LIKE v2":{"K":"'.$Key.'","V":"%'.$Value.'%"}}}');
        break;

        case '@':
            // Keyword
            if (mb_substr($Q, 0, 7) == '@Random')
            {
                    $IDs = Data::Read($Args['Point'],
                        '{"Fields":'.$Fields.',"Where":{"1=1":{"I":"I"}}}');

                    if (!is_numeric($C = mb_substr($Q, 7)))
                        $C = 1;

                    $IDs2 = array();
                    for ($a = 1; $a <= $C; $a++)
                        $IDs2[] = $IDs[array_rand($IDs)];
                    
                    $IDs = $IDs2;
            }
           if (mb_substr($Q, 0, 4) == '@All')
           {
                    $IDs = Data::Read($Args['Point'],
                        '{"Fields":'.$Fields.',"Where":{"1=1":{"I":"I"}}}');
           }
        break;

        case '>':
            // Greater
            list ($Key, $Value) = explode ('>', mb_substr ($Q, 1));
            $IDs = Data::Read($Args['Point'], '{"Fields":'.$Fields.',"Where":{"k1 = v1&&k2 > v2":{"K":"'.$Key.'","V":"'.$Value.'"}}}');
        break;

        case '<':
            // Lesser
            list ($Key, $Value) = explode ('<', mb_substr ($Q, 1));
            $IDs = Data::Read($Args['Point'], '{"Fields":'.$Fields.',"Where":{"k1 = v1&&k2 < v2":{"K":"'.$Key.'","V":"'.$Value.'"}}}');
        break;

        case '!':
            // Not equal
            list ($Key, $Value) = explode ('!', mb_substr ($Q, 1));
            $IDs = Data::Read($Args['Point'], '{"Fields":'.$Fields.',"Where":{"k1 = v1&&k2 != v2":{"K":"'.$Key.'","V":"'.$Value.'"}}}');
        break;

        case '.':
            // Statistics by key
            switch (mb_substr($Q, 1, 4))
            {
                case 'Max=':
                    $IDs = Data::Read($Args['Point'], '{"Fields":'.$Fields.',"Sort":{"Key":"V","Dir":"DESC"},"Where":{"k1 = v1":{"K":"'.mb_substr($Q, 5).'"}}}');
                    $IDs = array(min($IDs));
                break;

                case 'Min=':
                    $IDs = Data::Read($Args['Point'], '{"Fields":'.$Fields.',"Sort":{"Key":"V","Dir":"ASC"},"Where":{"k1 = v1":{"K":"'.mb_substr($Q, 5).'"}}}');
                    $IDs = array(min($IDs));
                break;
            }

        break;

        case '-':
            // Relations
            switch (mb_substr($Q, 1, 5))
            {
                case 'chld:': // Child
                    mb_substr($Q, 6);
                break;

                case 'prnt:': // Parent
                    mb_substr($Q, 6);
                break;

                case 'sbln:': // Sibling
                    mb_substr($Q, 6);
                break;

                case 'uncl:': // Uncle
                    mb_substr($Q, 6);
                break;

                case 'grnd:': // GrandParent
                    mb_substr($Q, 6);
                break;

                case 'plmn:': // Plemyannik =)
                    mb_substr($Q, 6);
                break;

                case 'next:': // Next
                    mb_substr($Q, 6);
                break;
                
                case 'prev:': // Previous
                    mb_substr($Q, 6);
                break;
            }
        break;
    
        default:
            if ($IDs === null)
                $IDs = Data::Read($Args['Point'],
                    '{"Fields":'.$Fields.',"Where":{"k1 = v1":{"I":"'.$Q.'"}}}');
        break;
    }

    if (is_array ($IDs))
        foreach($IDs as $ID)
            $Oz[$ID['I']] = $ID['I'];
    else
        $Oz = null;
        
    return $Oz;
}

function F_SQL3D_Sort ($Args)
{
    $DL = '{"Fields":["I"],"Sort":{"Key":"V","Direction":"'.$Args['Direction'].'"},"Where":{"k1 IN (v1) AND k2 = v2":{"I":'.json_encode($Args['Name']).', "K":"'.$Args['Key'].'"}}}';

    $RAW = Data::Read($Args['Point'], $DL);

    $Data = array();
    
    if ($RAW !== null)
        foreach($RAW as $Row)
            $Data[] = $Row['I'];
    else
        $Data = null;

    return $Data;
}

function F_SQL3D_Variants ($Args)
{
    $DL = '{"Fields":["V"],"Where":{"k1 = v1":{"K":"'.$Args['Key'].'"}}}';

    $RAW = Data::Read($Args['Point'], $DL);

    if ($RAW)
        {
            $Data = array();
            foreach($RAW as $Row)
                $Data[$Row['V']] = $Row['V'];
        }
        else
            $Data = null;

    return $Data;
}