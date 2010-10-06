<?php

if (!self::$Collection->Queried)
    self::$Collection->Query(self::$ID);

if (isset(self::$In['SortType']))
    $SortType = self::$In['SortType'];
else
    $SortType = 'DESC';

if (isset(self::$In['SortKey']) and !empty(self::$In['SortKey']))
    self::$Collection->Sort(self::$In['SortKey'], $SortType);
else
    self::$Collection->Sort('CreatedOn', $SortType);

if (!isset(self::$Mode) or empty(self::$Mode))
    self::$Mode = self::$Plugin;

self::$Collection->Load();

$Data = array();
foreach (self::$Collection->_Items as $Name => $Object)
        $Data[$Object->Name] = $Object->Data();

View::Body(json_encode($Data));