<?php

  $Clients = Data::Read('JSONFS', '{"I":"ER/Clients"}');

  if (isset(self::$In['ClientID']))
  {
      $ClientID = self::$In['ClientID'];
      
      if ($Clients->$ClientID->Passkey == self::$In['Passkey'])
      {
          $Namespace = self::$Mode;

          if (isset($Clients->$ClientID->Allowed->$Namespace) and in_array(self::$ID, $Clients->$ClientID->Allowed->$Namespace))
          {
              $Result = Code::E(self::$Mode,self::$ID, self::$In['Args'], self::$Aspect);

              switch (self::$Interface)
              {
                  case 'json':
                    $Result = json_encode($Result);
                  break;

                  default:
                    ;
                  break;
              }
          }
          else
              $Result = 'Not allowed';
      }
      else
          $Result = 'Authorization failed.';
  }
  else
      $Result = 'No authorization.';

  echo $Result;