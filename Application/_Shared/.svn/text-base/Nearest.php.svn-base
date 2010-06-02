<?php

$Buffered = '';
if (!isset(self::$In['Count']))
    self::$In['Count'] = 5;

if (self::$Aspect)
    self::$Collection = new Collection(self::$Aspect);

self::$Object->Load(self::$ID);

for ($Radius = 0; $Radius<=10; $Radius++)
{
    self::$Collection->Query('>Geo:Latitude>'.(self::$Object->Get('Geo:Latitude')-$Radius));
    self::$Collection->Intersect('<Geo:Latitude<'.(self::$Object->Get('Geo:Latitude')+$Radius));
    self::$Collection->Intersect('>Geo:Longitude>'.(self::$Object->Get('Geo:Longitude')-$Radius));
    self::$Collection->Intersect('<Geo:Longitude<'.(self::$Object->Get('Geo:Longitude')+$Radius));
    
    if (self::$Collection->Length >= self::$In['Count'])
        break;
}
unset(self::$Collection->Names[self::$ID]);

include Engine.Apps.'_Shared/List.php';