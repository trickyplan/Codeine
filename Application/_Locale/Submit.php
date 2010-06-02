<?php

  $Token = new Object('_Locale', Server::Get('Token'));
  $Token->Add(Client::$Language, Server::Get('Text'));
  $Token->Save ();

  Page::Body(Server::Get('Text'));