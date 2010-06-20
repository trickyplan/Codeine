<?php

function F_Image_Validate($Args)
{
    if (empty($Args['Value']))
        return true;

    if (isset($_FILES[$Args['Key']]['name']))
    {
        $AllowedTypes = array('image/jpeg','image/png','image/jpg','image/gif');
        $AllowedExtensions = array('jpg','jpeg','gif','png');
        $Pcs = explode('.',mb_strtolower($_FILES[$Args['Key']]['name']));
        
        if (in_array($_FILES[$Args['Key']]['type'], $AllowedTypes) && in_array($Pcs[sizeof($Pcs)-1], $AllowedExtensions))
            return true;
        else
            return false;
    }
    return true;
    
}

function F_Image_Input($Args)
{
    $Extension = explode('.',$_FILES[$Args['Key']]['name']);
    
    $Extension = mb_strtolower($Extension[sizeof($Extension)-1]);
    $Dir = $Args['Scope'].'/'.$Args['Name'].'/';

    if (!is_dir($Dir))
        mkdir($Dir, 0777, true);

    $NewName = uniqid($Args['Name'],true).'.'.$Extension;
    move_uploaded_file($Args['Value'], Root.Data.$Dir.$NewName);

    $EXIF = Code::E('File/Parsers','Parse', Root.Data.$Dir.$NewName, 'EXIF');
    if (!is_array($EXIF))
        $EXIF = array();

    $Result = array('Value' => $Dir.$NewName);
    $Result = array_merge($Result, $EXIF);

    return $Result;
}

function F_Image_Output($Args)
{
    return $Args;
}