<?php

self::$Collection->Query('@All');

if (isset(self::$Mode))
    self::$Collection->Sort(self::$In['SortKey'], 'DESC');
else
    self::$Collection->Sort('Rating:Overall', 'DESC');

if (!isset(self::$In['Page']))
    self::$In['Page'] = 1;

self::$Collection->Page(self::$In['Page']);

self::$Collection->Load();

if (self::$Collection->Length>0)
    Page::Add(Page::Fusion('Objects/'.self::$Name.'/'.self::$Name.'_Rating', self::$Collection, false));
else
    Page::Nest(array('Application/'.self::$Name.'/Empty','Application/_Shared/Empty'));