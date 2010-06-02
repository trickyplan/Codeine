<?php
/**
 * @package Plugins
 * @name Ticket/Cleanup
 * @author BreathLess
 * @version 0.1
 * @copyright BreathLess, 2010
 */

self::$Collection->Query('@All');
self::$Collection->Slice(0,200);
self::$Collection->Load();

foreach (self::$Collection->_Items as $Item)
    if ($Item->Get('User') === null)
        $Item->Erase();
