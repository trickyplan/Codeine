<?php

  self::$Object = new Object(self::$Name, Client::$Language);
  if (self::$Object->Get('Owner') == Client::$UID)
      {
        self::$Object->Add(Server::Get('Token'), Server::Get('Text'));
        self::$Object->Save ();
        Page::Body(Server::Get('Text'));
      }