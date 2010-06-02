<?php

class Search
{
    private static $_Engines;


    public static function Mount ($Point, $Method, $DSN)
    {
        self::$_Engines[$Point]['Point']  = Code::E('Search/Engines', 'Mount', $DSN, $Method);
        self::$_Engines[$Point]['Method'] = $Method;
        return true;
    }

    public static function Unmount ()
    {
        return self::$_Engines[$Point] = Code::E('Search/Engines', 'Unmount', self::$_Engines[$Point],self::$_Engines[$Point]['Method']);
    }

    public static function Add($Point, $ID, $Document)
    {
        return Code::E('Search/Engines', 'Add', array('ID'=>$ID, 'Document'=>$Document), self::$_Engines[$Point]['Method']);
    }

    public static function Update($Point, $ID, $Document)
    {
        return Code::E('Search/Engines', 'Update', array('ID'=>$ID, 'Document'=>$Document), self::$_Engines[$Point]['Method']);
    }

    public static function Remove($Point, $ID)
    {
        return Code::E('Search/Engines', 'Remove', array('ID'=>$ID), self::$_Engines[$Point]['Method']);
    }

    public static function Search($Point, $Query, $Args)
    {
        return Code::E('Search/Engines', 'Query', array('Query'=>$Query,'Args'=>$Args), self::$_Engines[$Point]['Method']);
    }
}
