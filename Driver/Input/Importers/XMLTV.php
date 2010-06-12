<?php
/**
 * Codeine Platform
 * @package Drivers
 * @name XMLTV Importer
 * @author BreathLess
 * @version 0.1
 * @copyright BreathLess, 2010
 */

function F_XMLTV_Import($XML)
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
          
          $Programs[(string)$Programm['channel']][]
            = array('Start' =>strptime((string) $Programm['start'],DATE_RSS),
                    'Stop'  =>strptime((string) $Programm['stop'],DATE_RSS),
                    'Title' =>(string)$Programm->title,
                    'Description'=> (string)$Programm->desc,
                    'Category' => $Category);
      }

      return array('Channels'=>$Channels, 'Programs'=>$Programs);
  }