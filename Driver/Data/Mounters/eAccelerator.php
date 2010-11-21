<?php

function F_eAccelerator_Mount ($Args)
{
    return extension_loaded('eaccelerator');
}

function F_eAccelerator_Unmount ($Args)
{
    return true;
}

function F_eAccelerator_Create ($Args)
{
    return eaccelerator_put ($Args['DDL']['I'], serialize($Args['DDL']['V']));
}

function F_eAccelerator_Read ($Args)
{
    return unserialize(eaccelerator_get($Args['DDL']['I']));
}

function F_eAccelerator_Update ($Args)
{
    return eaccelerator_put ($Args['DDL']['I'],serialize ($Args['DDL']['V']));
}

function F_eAccelerator_Delete ($Args)
{
    return eaccelerator_rm ($Args['DDL']['I']);
}

function F_eAccelerator_Exist ($Args)
{
    if (null !== eaccelerator_get ($Args['DDL']['I']))
        return true;
    else
        return false;
}