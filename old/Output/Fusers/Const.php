<?php

  function F_Const_Fusion($Args) 
  {

    //if ($Args['Object']->Get('Handle')!==null)
    //    $Handle = $Args['Object']->Get('Handle');
    // else
        $Handle = $Args['Object']->Name;

    $OK = array
    (
      '<language/>',
      '<interface/>',
      '<app/>',
      '<plugin/>',
      '<id/>',
      '<aspect/>',
      '<name/>',
      '<scope/>');

    $OV = array
    (
      Client::$Language,
      Application::$Interface,
      Application::$Name,
      Application::$Plugin,
      Application::$ID,
      Application::$Aspect,
      $Handle,
      $Args['Object']->Scope);

    if (Client::$Authorized)
    {
        $OK[] = '<uname/>';
        $OV[] = Client::$User->Name;
    }
    else
    {
        $OK[] = '<uname/>';
        $OV[] = '';
    }

    
    if (isset(Client::$Face))
    {
        $OK[] = '<facetype/>';
        $OV[] = Client::$Face->Scope;
        $OK[] = '<facename/>';
        $OV[] = Client::$Face->Name;
    }

    $Args['Structure'] = str_replace($OK, $OV, $Args['Structure']);

    return $Args['Structure'];

  }