<?php
/**
 * @package Service
 * @name RPC
 * @author BreathLess
 * @version 0.1
 * @copyright BreathLess, 2010
 */

  $Args = json_decode(Server::Get('Args'));
  die (Code::E(Server::Get('N'),Server::Get('F'), $Args,Server::Get('D')));