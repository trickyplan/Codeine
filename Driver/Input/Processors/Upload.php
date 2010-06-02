<?php

    function F_Upload_Process($Args)
    {
        $Extension = explode('.',$_FILES[$Args['Key']]['name']);
        $Extension = mb_strtolower($Extension[sizeof($Extension)-1]);
        $NewName = $Args['Scope'].'/'.$Args['Name'].'/'.uniqid($Args['Name'],true).'.'.$Extension;

        move_uploaded_file($Args['Value'], Root.Data.$NewName);
        return $NewName;
    }