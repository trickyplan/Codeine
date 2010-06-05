<?php
/**
 * @package Plugins
 * @name 
 * @author BreathLess
 * @version 0.1
 * @copyright BreathLess, 2010
 */


 $I18N = new Collection('_Locale');
 $I18N->Query('@All');

 $I18N->Load();

 $Langs = array();
 foreach ($I18N->_Items as $Name => $Item)
 {
     $Data = $Item->Data();
     foreach ($Data as $Key => $Value)
     {
         if (!isset($Langs[$Key]))
         {
             $Langs[$Key] = new Object('_Language');
             $Langs[$Key]->Load($Key);
         }

         $Langs[$Key]->Add($Item->Name, $Value);
     }
 }

 foreach ($Langs as $Lang)
     $Lang->Save();