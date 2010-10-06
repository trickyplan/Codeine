<?php
/**
 * @package Plugins
 * @name 
 * @author BreathLess
 * @version 0.1
 * @copyright BreathLess, 2010
 */

  if (!self::$Object->Load(self::$ID))
    throw new WTF('404: Object Not Found', 4040);

  Client::$Agent->Set('Language', self::$Object->Name);