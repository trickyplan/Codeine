<?php

  function F_Human_Route($Call)
  {
      $Routes = new Collection('_Route');
      $Routes->Query('@All');
      $Routes->Load();

      foreach ($Routes->_Items as $Item)
      {
          switch ($Item->Get('Type'))
          {
              case 'Regex':

                  if (preg_match('@add(.*)@SsUu', $Call, $Pockets))
                      return $Pockets;

              break;

              case 'Strict':

              break;

              case 'Wildcard':

              break;
          }
      }

      return $Call;
  }