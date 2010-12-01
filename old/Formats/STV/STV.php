<?php
/**
 * Codeine Platform
 * @package Drivers
 * @name XMLTV STV
 * @author BreathLess
 * @version 5.0
 * @copyright BreathLess, 2010
 */

function _F_STV_Date($Date)
{
    preg_match('@(\d{4})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2}) ([\+\-]\d{4})@',$Date, $Matches);
    return mktime($Matches[4], $Matches[5], $Matches[6], $Matches[2], $Matches[3], $Matches[1]);
}

function F_STV_Decode($XML)
  {
      $XML = simplexml_load_string($XML);
      
      $Channels = array();
      $Programs = array();
      
      foreach ($XML->channel as $Channel)
          $Channels[(string)$Channel['id']] = array('Title'=>(string)$Channel->display_name);
      
      foreach ($XML->programme as $Programm)
      {
          $Category = array();

          foreach ($Programm->category as $cCategory)
              $Category[] = (string)$cCategory;
          
          $PR = array('Start' =>(string) _F_STV_Date($Programm['start']),
                    'Stop'  =>(string) _F_STV_Date($Programm['stop']),
                    'Title' =>(string)$Programm->title,
                    'Date' =>(string)$Programm->date);

          if (isset($Programm->anons->text))
              $PR['Description'] =(string) $Programm->anons->text;

          if (isset($Programm->anons->humans))
              foreach ($Programm->anons->humans as $Human)
                $PR['Humans'][] = (string)$Human;

          if (isset($Programm->anons->text))
              $PR['Description'] =(string) $Programm->anons->text;

          if (isset($Programm->anons->image))
              $PR['Image'] = (string) $Programm->anons->image;

          $Programs[(string)$Programm['channel']][] = $PR;
      }

      return array('Channels'=>$Channels, 'Programs'=>$Programs);
  }