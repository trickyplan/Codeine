<?php

  function F_SideBar_Process ($Data)
  {
      if (mb_strpos($Data, '<sidebar/>') == null)
          return $Data;

      if (Client::$Level == 0)
          return str_replace('<sidebar/>', '', $Data);
      
        $Names = array();
        $SCommands = new Collection('_SideBar');

        if (($Commands = Data::PoolGet('Sidebars')) === null)
        {
            $Commands = new Collection('_SideBar');
            $Commands->Query('@All');
            $Commands->Load();
            
            Data::PoolPut('Sidebars', $Commands);
        }

        foreach($Commands->_Items as $Command)
        {
            $Decision = true;

            if (null !== $Command->Get('Application') && !in_array(Application::$Name, $Command->Get('Application', false)))
                    $Decision = false;

            if (null !== $Command->Get('Plugin') && !in_array(Application::$Plugin, $Command->Get('Plugin', false)))
                    $Decision = false;

            //krumo($Command->Name, $Command, $Decision);
            
            if ($Decision)
                $SCommands->_Items[] = $Command;
        }
        
        $Output = array();
        $Sidebar = '';

        foreach($SCommands->_Items as $Command)
        {
            $Result['Title'] = '<l>Sidebar:'.$Command->Name.'</l>';
            $Result['Name'] = $Command->Name;

            if (null === ($Result['Group'] = $Command->Get('Group')))
                $Result['Group'] = 'Main';
            elseif ($Result['Group'] == 'Entity')
                $Result['Group'] = Application::$Name;

            $Result2 = Code::E('Sidebar/Commands', 'Sidebar', $Result, $Command->Name);

            $Sdbr = null;

            if ($Result2 !== false and $Result2 !== null and $Result2['Code'] !== null)
                $Sdbr = Page::Get('Sidebar/Enabled');

            if ($Result2['Code'] === null)
                $Sdbr = Page::Get('Sidebar/Disabled');

            if (is_array($Result2))
                foreach($Result2 as $Key => $Value)
                    $Sdbr = str_replace('<'.$Key.'/>', $Value, $Sdbr);

            if ($Result2 !== false and $Sdbr !== null)
                $Output[$Result['Group']][] = $Sdbr;
        }

        if (!empty($Output))
            foreach($Output as $Group => $Commands)
            {
                rsort($Commands);
                    $Sidebar .= '<div class="Sidebar_Group"><l>Sidebar:'.$Group.'</l></div>'.implode($Commands);
            }

      return str_replace('<sidebar/>',$Sidebar, $Data);
  }
