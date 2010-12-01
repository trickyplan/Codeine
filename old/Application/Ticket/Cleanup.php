<?php
/**
 * @package Plugins
 * @name Ticket/Cleanup
 * @author BreathLess
 * @version 5.0
 * @copyright BreathLess, 2010
 */

set_time_limit(0);
self::$Collection->Query('@All');
self::$Collection->Load();

foreach (self::$Collection->_Items as $Item)
    if ($Item->Get('User') === null)
        $Item->Erase();
