<?php

if (!self::$Collection->Queried)
    self::$Collection->Query(self::$ID);

if (isset(self::$In['SortKey']) and !empty(self::$In['SortKey']))
    self::$Collection->Sort(self::$In['SortKey'], 'DESC');
else
    self::$Collection->Sort('CreatedOn', 'DESC');

if (!isset(self::$Mode) or empty(self::$Mode))
    self::$Mode = self::$Plugin;

if (isset(self::$In['Count']))
    self::$Collection->Slice(0, self::$In['Count']);
else
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