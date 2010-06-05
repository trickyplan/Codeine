<?php

  $Token = new Object('_Language', Client::$Language);
  $Token->Add(Server::Get('Token'), Server::Get('Text'));
  $Token->Save ();

  Page::Body(Server::Get('Text'));