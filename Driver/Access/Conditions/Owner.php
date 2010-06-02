<?php

  function F_Owner_Check($Target)
  {
      if (Client::$Level>0)
          $R = ($Target->Get('Owner') == (string) Client::$User);
      else
          $R = false;

      return $R;
  }