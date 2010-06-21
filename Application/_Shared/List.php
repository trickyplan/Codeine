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

$Pagination = false;

if (isset(self::$In['Count']))
{
    if (self::$In['Count'] != 0)
        self::$Collection->Slice(0, self::$In['Count']);
}
else
    $Pagination = true;

if ($Pagination)
    {
        Page::Add(
        Code::E('Output/Elements/Paginators', 'Paginate',
            array(
                'Objects' => sizeof(self::$Collection->Names),
                'URL'=>self::$Call.'?Page='
                )
            )
            ,'pagination');

        if (!isset(self::$In['Page']))
            self::$In['Page'] = 1;
                self::$Collection->Page(self::$In['Page']);
    }

self::$Collection->Load();

if (sizeof(self::$Collection->_Items)>0)
    Page::Add( Page::Fusion('Objects/'.self::$Name.'/'.self::$Name.'_'.self::$Mode, self::$Collection) );
else
    Page::Nest(array('Application/'.self::$Name.'/Empty','Application/_Shared/Empty'));

if (self::$Interface == 'web')
    {
        $LiveURL = "/ajax/".self::$Name.'/List/'.self::$ID;
        Page::$Slots['LiveURL'] = $LiveURL;
    }