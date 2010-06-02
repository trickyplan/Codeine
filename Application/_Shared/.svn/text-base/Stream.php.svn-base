<?php

  // web/Activity/Stream/Follow/Person::BreathLess

$Buffered = array();
$Output = '';

self::$Object = new Object(self::$ID);
$Sources = self::$Object->Get(self::$Mode, false);
self::$Collection->Query('=Source=('.implode(',',$Sources).')');
self::$Collection->Merge('=Subject=('.implode(',',$Sources).')');

if (empty(self::$Aspect))
    self::$Aspect = 'Source';

if (isset(self::$In['SortKey']))
    self::$Collection->Sort(self::$In['SortKey'], 'DESC');
else
    self::$Collection->Sort('CreatedOn', 'DESC');

if (!isset(self::$In['Page']))
    self::$In['Page'] = 1;

self::$Collection->Page(self::$In['Page']);

self::$Collection->Load();

if (self::$Collection->Length>0)
    foreach (self::$Collection as $Object)
        {
            $Data = Page::Fusion('Objects/'.self::$Name.'/'.self::$Name.'_GroupBy_'.self::$Aspect, $Object, false);
            $Buffered[$Object->Get(self::$Aspect)][md5($Data)] = $Data;
        }
else
    Page::Nest(array('Application/'.self::$Name.'/Empty','Application/_Shared/Empty'));

foreach ($Buffered as $Key => $Text)
    $Output.= Page::Fusion('Groups/'.self::$Name.'/'.self::$Aspect, $Object, array('<Group/>'=>$Key, '<Text/>'=>implode('', $Text)));

Page::Add($Output);
