<?php

Log::Important('Online Checking');
$Persons = new Collection('Person');
$Persons->Query('<LastHit<'.(time()-60));
$Persons->Intersect('=Online=True');

$Persons->Load();

if ($Persons->Length > 0)
    foreach ($Persons->_Items as $Item)
        {
            echo $Item->Get('Title').' is offline <br/>';
            $Item->Del('Online');
            $Item->Set('Online', 'False');
            $Item->Save();
        }