<?php

$Buffered = array();
$Output = '';

if (!self::$Collection->Length)
    self::$Collection->Query(self::$ID);

if (isset(self::$In['SortKey']))
    self::$Collection->Sort(self::$In['SortKey'], 'DESC');
else
    self::$Collection->Sort('CreatedOn', 'DESC');

if (!isset(self::$Mode) or empty(self::$Mode))
    self::$Mode = self::$Plugin;
    
if (!isset(self::$In['Page']))
    self::$In['Page'] = 1;

self::$Collection->Page(self::$In['Page']);

self::$Collection->Load();

if (self::$Collection->Length>0)
    foreach (self::$Collection as $Object)
        $Buffered[$Object->Get(self::$Aspect)][] = Page::Fusion('Objects/'.self::$Name.'/'.self::$Name.'_'.self::$Mode, $Object, false);
else
    Page::Nest(array('Application/'.self::$Name.'/Empty','Application/_Shared/Empty'));

foreach ($Buffered as $Key => $Text)
    $Output.= '<h4>'.$Key.'</h4>'.implode('',$Text);

Page::Add($Output);
