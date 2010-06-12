<?php
/**
 * @package Service
 * @name XMLTV
 * @author BreathLess
 * @version 0.1
 * @copyright BreathLess, 2010
 */

  $XMLTV = file_get_contents(Engine.'A-ONE.xmltv');

  $Text = '20100607100000 -0400';
  preg_match('@(\d{4})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2}) ([\+\-]\d{4})@',$Text, $Matches);
  krumo($Matches);
  die();

  krumo(Code::E('Input/Importers','Import', $XMLTV, 'XMLTV'));

  die();