<?php

  function F_Female_Check($Target)
  {
      switch (Client::$Level)
      {
          case 1: $R = (Client::$User->Get('Sex')=='F'); break;
          case 2: $R = (Client::$Face->Get('Sex')=='F'); break;
          default:
              $R = null;
          break;
      }
      return $R;
  }

